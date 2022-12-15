// USER ADJUSTMENT =============================================================

var cf = {
  // characters per second which user reads in average. It determines how long a
  // certain full line has to be displayed until slides away.
  readingSpeed: 20,
  // minimal reading speed - if set differently from reading speed, continuously
  // adjusts the reading speed within the range
  minReadingSpeed: 10,
  // maximal reading speed - if set differently from reading speed, continuously
  // adjusts the reading speed within the range
  maxReadingSpeed: 30,
  // ratio between the number of words of completed sentences and others shown
  // in the subtitles frame - it simply determine how much the subtitles are
  // up to date
  completedRatio: 1.,
  // width of the subtitles frame in milimeters
  width: 100,
  // number of lines of the subtitles frame
  lineCount: 2,
  // font size of the subtitles text in milimeters
  fontSize: 4,
  // top and bottom padding of a single word in the subtitles frame in milimeters
  wordPaddingTB: 1,
  // left and right padding of a single word in the subtitles frame in milimeters
  wordPaddingLR: 1,
  // whether use an animation for moving subtitles up
  slideUp: true,
  // sliding up time
  slideUpTime: 300,
  // enable debugging
  debug: false,
  // log emission and display timestamps, and delay
  logTime: false,
  // log current reading speed
  logSpeed: false,
  // log resets with timestamps and the number of removed words
  logFluency: false
};

// GLOBAL LOGS =================================================================

var logRecord = "";

// FRAME STYLE SPECIFICATION ===================================================

var createFrameStyle = function(cf) {
  var frameStyle = {
    width: cf.width + "mm",
    height: (cf.lineCount * (cf.fontSize + 2 * cf.wordPaddingTB)) + "mm",
    lineHeight: 1
  };

  if (cf.debug) {
    frameStyle.outline = "4px dashed #999";
    frameStyle.overflow = "visible";
  } else {
    frameStyle.outline = "2px solid #999";
    frameStyle.overflow = "hidden";
  }

  return frameStyle;
}

var createWordStyle = function(cf) {
  var wordStyle = {
    padding: cf.wordPaddingTB + "mm " + cf.wordPaddingLR + "mm",
    fontSize: cf.fontSize + "mm"
  };

  return wordStyle;
}

// CONTROLLER COMMAND TYPES ====================================================

const CHANGE_TYPE = {
  STATUS: 0,
  UPDATE: 1,
  INSERT: 2,
  REMOVE: 3,
}

const SENTENCE_STATUS = {
  COMPLETE: 100,
  EXPECTED:  10,
  INCOMING:   1
}

var isComplete = function(status) {
  return status % SENTENCE_STATUS.COMPLETE == 0;
}

var isExpected = function(status) {
  return status % SENTENCE_STATUS.EXPECTED == 0 && !isComplete(status);
}

var isIncoming = function(status) {
  return !isExpected(status) && !isComplete(status);
}

// DEBUG =======================================================================

var logTime = function(element) {
  var logging = "time;" + element.dataset.emissionTime
  + ";" + element.dataset.displayTime
  + ";" + element.dataset.status
  + ";" + ((element.dataset.displayTime - element.dataset.emissionTime) / 1000)
  + ";" + Array.from(element.parentNode.children).indexOf(element)
  + ";" + element.innerText;
  logRecord += logging + "\n";
  console.log(logging);
}

// FRAME PROCESSING ============================================================

var processAll = function(frame, className, begin, end, mapping) {
  let words = [...frame.getElementsByClassName(className)];
  let begin_index = words.indexOf(begin), end_index = words.indexOf(end);

  if (begin_index == -1) return null;
  if (end_index == -1) return words.slice(begin_index).map(mapping).pop();
  return words.slice(begin_index, end_index + 1).map(mapping).pop();
}

var setWordWidth = function(frame, word) {
  let line = insertLine(frame); word.style.position = "absolute";
  
  // render word such that it is not visible to the reader
  word.style.top = "-1000px"; line.appendChild(word);
  word.dataset.width = word.offsetWidth;

  // get everything back to default
  word.remove(); line.remove();
  word.style.position = "static"; word.style.top = "";
  return word; 
}

var insertWord = function(frame, word) {
  let line = frame.children.length == 0 ? insertLine(frame) : frame.lastChild;

  line.style.display = "block"; line.appendChild(word);
  if (word.offsetWidth * 1 + line.dataset.width * 1 >= line.offsetWidth) {
    line = insertLine(frame); word.remove(); line.appendChild(word);
    line.dataset.width = word.offsetWidth;
  } else {
    line.dataset.width = line.dataset.width * 1 + word.offsetWidth;
  }
  word.dataset.width = word.offsetWidth;

  let currentTime = new Date().getTime();
  line.dataset.time = currentTime;
  
  word.dataset.displayTime =
    visibleLines(frame).includes(line) ? currentTime : 0;
}

var insertLine = function(frame) {
  let line = document.createElement('div');
  line.className = "line";
  
  line.dataset.width = 0;
  line.dataset.time = new Date().getTime();

  return frame.appendChild(line);
}

var isLineCompleted = function(line) {
  return [...line.children].filter(
    word => !isComplete(word.dataset.status)).length == 0;
}

var visibleLines = function(frame) {
  return [...frame.children].filter(
    word => word.style.display != "none").slice(0, cf.lineCount);
}

var bufferedLines = function(frame) {
  return [...frame.children].filter(
    word => word.style.display != "none").slice(cf.lineCount);
}

var removeComplete = function(line) {
  // this change later (probably)
  var tooLong = function(line) {
    return new Date().getTime() - line.dataset.time > 15000;
  };

  if ((line.style.display == "none" && isLineCompleted(line)) ||
      (line.style.display == "none" && tooLong(line))) {
    if (cf.logTime) [...line.children].forEach(e => logTime(e));
    
    let words = [...line.children]; line.remove();
    
    return words;
  }
  return [];
}

var ithSibling = function(element, className, index) {
  let words = [...document.getElementsByClassName(className)];
  let beginIndex = words.indexOf(element);

  if (beginIndex + index < words.length)
    return words[beginIndex + index];
}

// VIEWER ======================================================================

var insertAfter = function(newNode, referenceNode) {
  return referenceNode.parentNode.insertBefore(
    newNode, referenceNode.nextSibling);
}

var addColorClass = function(element, status) {
  if (isComplete(status)) element.className += " green";
  if (isExpected(status)) element.className += " orange";
  if (isIncoming(status)) element.className += " red";
  return element;
}

var assignStatus = function(element, status) {
  element.dataset.status = status;
  
  if (isIncoming(status)) element.className = "incoming word";
  if (isExpected(status)) element.className = "expected word";
  if (isComplete(status)) element.className = "complete word";
  if (!cf.debug) return element;
  
  if (isComplete(status)) element.className += " green";
  if (isExpected(status)) element.className += " orange";
  if (isIncoming(status)) element.className += " red";
  return element;
}

var Viewer = {
  // An element in DOM where the subtitles are displaying
  frame: document.getElementById("subtitles"),
  // history of all aligned complete words
  history: "",

  processStatus: function(change) {
    processAll(this.frame, "word", change.BEGIN, change.END,
      w => assignStatus(w, change.CONTENT));
  },
  
  processUpdate: function(change) {
    var mapping = function(element, changeBuffer) {
      return element.replaceWith(changeBuffer.shift());
    }

    processAll(this.frame, "word", change.BEGIN, change.END,
      w => mapping(w, change.CONTENT));
  },

  processRemove: function(change) {
    if (change.BEGIN && (change.BEGIN.parentNode.style.display == "none")
        && cf.logFluency) {
          let logging = "reset;" + new Date().getTime() + ";;;;;";
      console.log(logging);
      logRecord =+ logging + "\n";
    }

    let count = 0;
    let visibles = visibleLines(this.frame);

    var mapping = function(element) {
      element.parentNode.dataset.width -= element.dataset.width;
      
      if (visibles.includes(element.parentNode))
        count += 1;
      
      element.remove();
    }

    processAll(this.frame, "word", change.BEGIN, change.END,
      w => mapping(w));

    if (cf.logFluency && (count != 0)) {
      let logging = "removed;" + new Date().getTime() + ";" + count + ";;;;";
      console.log(logging);
      logRecord += logging + "\n";
    }

    // remove all empty lines
    [...this.frame.children].filter(e => e.children.length == 0)
      .map(e => e.remove());
  },

  processInsert: function(change) {
    for (let word of change.CONTENT) insertWord(this.frame, word);
  },

  update: function(changes) {
    for (let change of changes) {
      if (change.OPERATION == CHANGE_TYPE.STATUS) this.processStatus(change);
      if (change.OPERATION == CHANGE_TYPE.UPDATE) this.processUpdate(change);
      if (change.OPERATION == CHANGE_TYPE.REMOVE) this.processRemove(change);
      if (change.OPERATION == CHANGE_TYPE.INSERT) this.processInsert(change);
    }
  },

  refresh: function() {
    let line = this.frame.children.length != 0 ? this.frame.children[0] : null;

    if (line == null) return;

    processAll(this.frame, "line", line, null, l => {
      let string = removeComplete(l).map(w => w.textContent).join(" ");
      this.history += string == "" ? string : string + "\n";
    });

    let visibles = visibleLines(this.frame);
    line = visibles.length != 0 ? visibles[0] : null;

    if (line == null) return;

    if (bufferedLines(this.frame).length > 0)
      cf.readingSpeed = Math.min(cf.maxReadingSpeed, cf.readingSpeed + 1);
    else
      cf.readingSpeed = Math.max(cf.minReadingSpeed, cf.readingSpeed - 1);
    if (cf.logSpeed) {
      let logging = "speed;" + new Date().getTime() + ";" + cf.readingSpeed + ";;;;";
      console.log(logging);
      logRecord += logging + "\n";
    }

    let lineDisplayTime = new Date().getTime() - line.dataset.time;

    let lineCharcount = line.children.length - 1 + // spaces
      [...line.children].reduce((t, c) => t + c.dataset.charcount * 1, 0);
    let readingTime = lineCharcount / cf.readingSpeed;
    let readyToHide = lineDisplayTime > readingTime * 1000;

    let newLinesAvailable = bufferedLines(this.frame).length > 0;
    let tooLong = lineDisplayTime > (readingTime * 5 * 1000);

    var goodFlickering = function() {
      let words = visibles.map(l => [...l.children]).reduce((a, b) => a.concat(b));
      
      if (cf.completedRatio >= 0.5 && cf.completedRatio <= 1)
        return words.map(w => isExpected(w.dataset.status) || isComplete(w.dataset.status))
          .reduce((a, b) => a + b, 0) / words.length >= 1 - cf.completedRatio;
      if (cf.completedRatio <= 0.4 && cf.completedRatio >= 0)
        return words.map(w => isComplete(w.dataset.status))
          .reduce((a, b) => a + b, 0) / words.length >= 0.5 - cf.completedRatio;
    }();

    if ((readyToHide && newLinesAvailable && goodFlickering) || tooLong)
      this.scrollUp(line, cf.slideUpTime);
  },

  scrollUp: function(line, slidingTime) {
    let lineHeight = (- cf.fontSize - 2 * cf.wordPaddingTB) + "mm";
    let transition = "top " + slidingTime + "ms";

    // slide up all lines
    processAll(this.frame, "line", line, null,
      l => {l.style.transition = transition; l.style.top = lineHeight;});

    // update time for all lines except the first one
    let curTime = new Date().getTime();
    processAll(this.frame, "line", line.nextSibling, null,
      l => l.dataset.time = curTime)

    // update displayTime for visible lines
    processAll(this.frame, "line", bufferedLines(this.frame)[0], null,
      l => [...l.children].forEach(e => e.dataset.displayTime = curTime));

    let frame = this.frame;
    setTimeout(function() {
      line.style.display = "none";
      processAll(frame, "line", line, null,
        l => {l.style.transition = ""; l.style.top = "0px";});
    }, cf.slideUp ? slidingTime : 0);
  },

  clear: function() {
    [...this.frame.children].map(ch => ch.remove());

    this.history = "";
  }
}

// TEXT PROCESSING =============================================================

var toElements = function(words, status) {
  var createWord = function(string, status) {
    let element = document.createElement('span');
    Object.assign(element.style, createWordStyle(cf));

    element = assignStatus(element, status);
    element.dataset.charcount = string.length;
    element.dataset.emissionTime = new Date().getTime();

    element.innerHTML = string;
    return element;
  }

  return words.map(s => createWord(s, status));
}

var indexesOfDifferentWords = function(original, other) {
  originalWords = original.split(" ");
  otherWords = other.split(" ");
  let length = Math.min(originalWords.length, otherWords.length);
  
  let result = [];
  for (let index = 0; index < length; index++)
    if (originalWords[index] != otherWords[index]) result.push(index);

  return result.length != 0 ? result : [length];
}

var splitWithRest = function(string, separator, limit) {
  let splitted = string.split(separator);
  return splitted.slice(0, limit).concat([splitted.slice(limit).join(separator)]);
}

var jsonEvents = function(string) {
  let [id, status, sentence] = splitWithRest(string, " ", 2);
  
  return {
    complete: isComplete(status) ? [[id * 1, status * 1, sentence]] : [],
    expected: isExpected(status) ? [[id * 1, status * 1, sentence]] : [],
    incoming: isIncoming(status) ? [[id * 1, status * 1, sentence]] : []
  }
}

// CONTROLLER ==================================================================

var Controller = {
  // Current state of the controller - all sentences that are active, stored as
  // json object that has been received from online-text-flow server
  state: {},

  // Last ID used by incoming message
  lastID: 0,

  // Whether a change occured during processing data in update method
  changeOccurs: false,

  toJSON: function(operation, begin, end, content) {
    return {OPERATION: operation, BEGIN: begin, END: end, CONTENT: content};
  },

  processComplete: function(data) {
    let id = data[0], status = data[1], text = data[2];
    let info = this.state[id]; delete this.state[id];

    return [this.toJSON(CHANGE_TYPE.STATUS, info.begin, info.end, status)];
  },

  // expected and incoming together
  processExcoming: function(data) {
    let id = this.lastID = data[0], status = data[1], text = data[2];
    let begin = (id in this.state) ? this.state[id].begin : null;

    let indexes = [], info = {}, changeBegin = null, changes = [];
    if (begin != null) info = this.state[id]; else info = this.state[id] = {};

    if (begin != null) {
      if (!this.changeOccurs && info.sentence == text) return [];

      indexes = indexesOfDifferentWords(info.sentence, text);

      if (indexes.length > 0)
        changeBegin = ithSibling(info.begin, "word", indexes[0]);
      
      changes.push(
        this.toJSON(CHANGE_TYPE.STATUS, info.begin, info.end, status));
    }

    let rest = [];
    if (!this.changeOccurs && begin != null) {
      Object.keys(this.state).sort().filter(k => k > this.lastID)
        .forEach(k => processAll(document.getElementById("subtitles"), "word",
        this.state[k].begin, this.state[k].end, w => rest.push(w)));
      changes.push(this.toJSON(CHANGE_TYPE.REMOVE, changeBegin, null, null));
    }
    
    //this.changeOccurs = true;
    let words = toElements(text.split(" ").slice(indexes[0]), status);

    changes.push(this.toJSON(CHANGE_TYPE.INSERT, null, null, words));
    changes.push(this.toJSON(CHANGE_TYPE.INSERT, null, null, rest));

    info.begin = indexes[0] == 0 || begin == null ? words[0] : begin;
    info.sentence = text; info.status = status;
    info.end = words[words.length - 1];

    return changes;
  },

  update: function(data) {
    let changes = []; this.changeOccurs = false;

    for (let complete of data.complete) {
      changes = changes.concat(this.processExcoming(complete));
      changes = changes.concat(this.processComplete(complete));
    }
    for (let expected of data.expected)
      changes = changes.concat(this.processExcoming(expected));
    for (let incoming of data.incoming)
      changes = changes.concat(this.processExcoming(incoming));

    return changes;
  },

  clear: function() {
    this.state = {}; this.lastID = 0; this.changeOccurs = false;
  }
}

var GUI = {
  addConfiguration: function() {
    let frame = document.getElementById("subtitles");
    let form = document.createElement('form'); form.id = "config";
    form = insertAfter(form, frame);
    form.insertAdjacentHTML('beforeend',
      '<label for="reading-speed">Reading speed (chars per second):\
        </label><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="number" id="reading-speed" \
        name="reading-speed" value="10" min="1"><br><br>');
    form.insertAdjacentHTML('beforeend',
      '<label for="min-reading-speed">Min. reading speed (chars per second):\
        </label><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="number" id="min-reading-speed" \
        name="min-reading-speed" value="10" min="1"><br><br>');
    form.insertAdjacentHTML('beforeend',
      '<label for="max-reading-speed">Max. reading speed (chars per second):\
        </label><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="number" id="max-reading-speed" \
        name="max-reading-speed" value="30" max="100"><br><br>');
    form.insertAdjacentHTML('beforeend',
      '<label for="completed-ratio">Flicker:</label><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="range" id="completed-ratio" \
        name="completed-ratio" value="1" min="0" max="1" step="0.1"><br><br>');
    form.insertAdjacentHTML('beforeend',
      '<label for="width">Width (mm):</label><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="number" id="width" \
        name="width" value="100" min="1"><br><br>');
    form.insertAdjacentHTML('beforeend',
      '<label for="line-count">Line count:</label><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="number" id="line-count" \
        name="line-count" value="2" min="1"><br><br>');
    form.insertAdjacentHTML('beforeend',
      '<label for="font-size">Font size (mm):</label><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="number" id="font-size" \
        name="font-size" value="4" min="0.1" step="0.1"><br><br>');
    form.insertAdjacentHTML('beforeend',
      '<label for="word-padding-tb">Top/bottom word padding (mm):</label><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="number" id="word-padding-tb" \
        name="word-padding-tb" value="1" min="0.1" step="0.1"><br><br>');
    form.insertAdjacentHTML('beforeend',
      '<label for="word-padding-lr">Left/right word padding (mm):</label><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="number" id="word-padding-lr" \
        name="word-padding-lr" value="1" min="0.1" step="0.1"><br><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="checkbox" id="slide-up" name="slide-up" value="slide-up"\
        checked>');
    form.insertAdjacentHTML('beforeend',
      '<label for="slide-up">Slide up animation</label><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="checkbox" id="debug" name="debug" value="debug">');
    form.insertAdjacentHTML('beforeend',
      '<label for="debug">Debug</label><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="checkbox" id="log-speed" name="log-speed" value="log-speed">');
    form.insertAdjacentHTML('beforeend',
      '<label for="log-speed">Log reading speed</label><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="checkbox" id="log-time" name="log-time" value="log-time">');
    form.insertAdjacentHTML('beforeend',
      '<label for="log-time">Log timestamps</label><br><br>');
    form.insertAdjacentHTML('beforeend',
      '<input type="checkbox" id="log-fluency" name="log-fluency" value="log-fluency">');
    form.insertAdjacentHTML('beforeend',
      '<label for="log-fluency">Log fluency</label><br><br>');
    form.insertAdjacentHTML('beforeend',
      '<div style="text-align: center;">\
        <input id="apply" type="button" value="Apply">\
        <input id="close" type="button" value="Close">\
      </div>');
  },

  addListeners: function() {
    // show configuration
    document.getElementById("subtitles").onclick = function() {
      document.getElementById("config").style.display = "block";
    }
    // apply configuration
    document.getElementById("apply").onclick = function() {
      var elementById = id => document.getElementById(id);
      // reading speed
      cf.readingSpeed = Number(elementById("reading-speed").value);
      // min reading speed
      cf.minReadingSpeed = Number(elementById("min-reading-speed").value);
      // max reading speed
      cf.maxReadingSpeed = Number(elementById("max-reading-speed").value);
      // completed ratio
      cf.completedRatio = Number(elementById("completed-ratio").value);
      // width
      cf.width = Number(elementById("width").value);
      // line count
      cf.lineCount = Number(elementById("line-count").value);
      // font size
      cf.fontSize = Number(elementById("font-size").value);
      // word padding top/bottom
      cf.wordPaddingTB = Number(elementById("word-padding-tb").value);
      // word padding let/right
      cf.wordPaddingLR = Number(elementById("word-padding-lr").value);
      // slide up
      cf.slideUp = elementById("slide-up").checked;
      // debug
      cf.debug = elementById("debug").checked;
      // logging timestamps
      cf.logTime = elementById("log-time").checked;
      // logging current reading speed
      cf.logSpeed = elementById("log-speed").checked;
      // log resets with timestamps and the number of removed words
      cf.logFluency = elementById("log-fluency").checked;

      Object.assign(elementById("subtitles").style, createFrameStyle(cf));
    }
    // close configuration
    document.getElementById("close").onclick = function() {
      document.getElementById("config").style.display = "none";
    }
  },

  addHistoryDownload: function(viewer) {
    let form = document.getElementById("config");
    form.insertAdjacentHTML('beforeend',
      '<input type="button" id="history-dwn" value="Download history"/>');

    document.getElementById("history-dwn").addEventListener("click", function(){
      download("history.txt", viewer.history);
    }, false);
  },

  removeConfiguration: function() {
    document.getElementById("config").remove();
  }
}

function download(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);

  element.style.display = 'none';
  document.body.appendChild(element);

  element.click();

  document.body.removeChild(element);
}

var Subtitler = {
  viewer: Viewer,
  controller: Controller,
  GUI: GUI,
  refreshId: null,

  start: function() {
    Object.assign(document.getElementById("subtitles").style,
      createFrameStyle(cf));

    GUI.addConfiguration();
    GUI.addListeners();
    GUI.addHistoryDownload(this.viewer);

    let refresh = this.viewer.refresh;
    refresh = refresh.bind(this.viewer);
    
    refreshId = setInterval(refresh, 300);
  },

  update: function(data) {
    if (!data) return;

    if (data.complete == undefined)
      data = jsonEvents(data);

    //if (cf.debug) console.log(data);
    data = Controller.update(data);

    //if (cf.debug) console.log(data);
    Viewer.update(data);
  },

  stop: function() {
    GUI.removeConfiguration();

    clearInterval(this.refreshId);

    this.controller.clear();
    this.viewer.clear();
  },

  restart: function() {
    this.stop();
    this.start();
  }
}