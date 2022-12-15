# Continuous Rating

Continuous Rating is a method for evaluating simultaneous speech translation (SST).

Continuous Rating was first used in the paper [Continuous Rating as Reliable Human Evaluation of Simultaneous Speech Translation](https://www.statmt.org/wmt22/pdf/2022.wmt-1.9.pdf) as a tool for measuring the quality of simultaneous translation by human annotators. Later, the same evaluation platform was employed for the human evaluation of submitted systems in the simultaneous speech translation shared task in IWSLT 2022. See [Findings of the IWSLT 2022 Evaluation Campaign](https://aclanthology.org/2022.iwslt-1.10/) for more details.

In this repository, we provide an web-based application for simulating live events with subtitling and Continuous Rating as its evaluation.

## Subtitles

Subtitles are loaded from the `subtitles` directory. The format of the file follows the input format of [Subtitler](https://github.com/ufal/subtitler).

## Videos

Videos are loaded from the `videos` directory.

## Database

Database is created by `create_database.sql` script.

## CREDITS

If you use Continuous Rating evaluation application, please cite the following:

```
@InProceedings{javorsk-machek-bojar:2022:WMT,
  author    = {Javorský, Dávid  and  Macháček, Dominik  and  Bojar, Ondřej},
  title     = {{Continuous Rating as Reliable Human Evaluation of Simultaneous Speech Translation}},
  booktitle      = {Proceedings of the Seventh Conference on Machine Translation},
  month          = {December},
  year           = {2022},
  address        = {Abu Dhabi},
  publisher      = {Association for Computational Linguistics},
  pages     = {154--164},
  url       = {https://aclanthology.org/2022.wmt-1.9}
}
```