# Abstract Babel

## Background

## Setup

```
git clone https://github.com/nlisgo/abstract-babel.git
cd abstract-babel
cp config.php.example config.php # Amend settings
composer install
docker-compose up -d
```

Visit: <http://localhost:8080/babel?doi=10.7554/eLife.36998&from=en&to=fr>

Swap the doi, from and to values to translate your favourite abstract.

## Stored approved translations

A rudimentary store of translations has been added to demonstrate the behaviour if an approved translation has been supplied.

Add approved translations in json in the location: `./translations/[doi]/[to_lang].json`

It should have the structure:

```
# See: ./translations/10.7554/eLife.06813/fr.json
{
    "abstract": "La souris sylvestre (genre Peromyscus) est le mammifère le plus abondant en Amérique..."
}
```

To return the stored translation <http://localhost:8080/babel?doi=10.7554/eLife.06813&from=en&to=fr>

To avoid checking the store and request a new translation: <http://localhost:8080/babel?doi=10.7554/eLife.06813&from=en&to=fr&check-store=0>

## Examples

The following examples were used to test different translation tools, and to provide content for the design of prototypes.

### Example 1

This first example is a recent article publised in eLife.

**The Natural History of Model Organisms: Peromyscus mice as a model for studying natural variation**
by Nicole L Bedford, Hopi E Hoekstra

DOI: [https://doi.org/10.7554/eLife.06813.001](10.7554/eLife.06813.001)

The original abstract was in English:

*The deer mouse (genus Peromyscus) is the most abundant mammal in North America, and it occupies almost every type of terrestrial habitat. It is not surprising therefore that the natural history of Peromyscus is among the best studied of any small mammal. For decades, the deer mouse has contributed to our understanding of population genetics, disease ecology, longevity, endocrinology and behavior. Over a century's worth of detailed descriptive studies of Peromyscus in the wild, coupled with emerging genetic and genomic techniques, have now positioned these mice as model organisms for the study of natural variation and adaptation. Recent work, combining field observations and laboratory experiments, has lead to exciting advances in a number of fields—from evolution and genetics, to physiology and neurobiology.*

One want to retrieve a translation into French e.g. using [DeepL](www.DeepL.com/Translator)

*La souris sylvestre (genre Peromyscus) est le mammifère le plus abondant en Amérique du Nord et occupe presque tous les types d'habitats terrestres. Il n'est donc pas surprenant que l'histoire naturelle de Peromyscus soit parmi les mieux étudiées de tous les petits mammifères. Pendant des décennies, la souris sylvestre a contribué à notre compréhension de la génétique des populations, de l'écologie des maladies, de la longévité, de l'endocrinologie et du comportement. Plus d'un siècle d'études descriptives détaillées de Peromyscus dans la nature, associées à des techniques génétiques et génomiques émergentes, ont positionné ces souris comme des organismes modèles pour l'étude de la variation et de l'adaptation naturelles. Des travaux récents, combinant des observations sur le terrain et des expériences en laboratoire, ont mené à des avancées passionnantes dans un certain nombre de domaines - de l'évolution et de la génétique à la physiologie et à la neurobiologie.*

or [Amazon AWS Translate](https://aws.amazon.com/translate/)

*La souris des cerfs (genre Peromyscus) est le mammifère le plus abondant en Amérique du Nord, et elle occupe presque tous les types d'habitat terrestre. Il n'est donc pas surprenant que l'histoire naturelle de Peromyscus soit l'une des meilleures étudiées chez tous les petits mammifères. Depuis des décennies, la souris des cerfs contribue à notre compréhension de la génétique des populations, de l'écologie des maladies, de la longévité, de l'endocrinologie et du comportement. Depuis plus d'un siècle, des études descriptives détaillées de Peromyscus dans la nature, associées à des techniques génétiques et génomiques émergentes, ont maintenant positionné ces souris comme pour l'étude de la variation naturelle et de l'adaptation. Des travaux récents, combinant des observations sur le terrain et des expériences en laboratoire, ont mené à des avancées passionnantes dans un certain nombre de domaines - de l'évolution et de la génétique à la physiologie et à la neurobiologie.*

or [Google Translate](https://translate.google.co.uk)

*La souris de cerf (genre Peromyscus) est le mammifère le plus abondant en Amérique du Nord, et elle occupe presque tous les types d'habitats terrestres. Il n'est donc pas surprenant que l'histoire naturelle de Peromyscus soit parmi les mieux étudiées de tous les petits mammifères. Pendant des décennies, la souris du chevreuil a contribué à notre compréhension de la génétique des populations, de l'écologie des maladies, de la longévité, de l'endocrinologie et du comportement. Plus d'un siècle d'études descriptives détaillées de Peromyscus dans la nature, couplé avec des techniques génétiques et génomiques émergentes, ont maintenant positionné ces souris comme des organismes modèles pour l'étude de la variation naturelle et de l'adaptation. Des travaux récents, combinant observations sur le terrain et expériences de laboratoire, ont conduit à des avancées passionnantes dans un certain nombre de domaines, de l'évolution et de la génétique à la physiologie et à la neurobiologie.*

### Example 2

The second example is taken from [bioRxiv](https://www.biorxiv.org).

**Real-time functional connectivity-based neurofeedback of amygdala-frontal pathways reduces anxiety**
by Zhiying Zhao, Shuxia Yao, Keshuang Li, Cornelia Sindermann, Feng Zhou, Weihua Zhao, Jianfu Li, Michael Luehrs, Rainer Goebel, Keith Kendrick and Benjamin Becker

doi: [https://doi.org/10.1101/308924](10.1101/308924)

Original Abstract:

Deficient emotion regulation and exaggerated anxiety represent a major transdiagnostic psychopathological marker. On the neural level these deficits have been closely linked to impaired, yet treatment-sensitive, prefrontal regulatory control over the amygdala. Gaining direct control over these pathways could therefore provide an innovative and promising strategy to regulate exaggerated anxiety. To this end the current proof-of-concept study evaluated the feasibility, functional relevance and maintenance of a novel connectivity-informed real-time fMRI neurofeedback training. In a randomized within-subject sham-controlled design high anxious subjects (n = 26) underwent real-time fMRI-guided training to enhance connectivity between the ventrolateral prefrontal cortex (vlPFC) and the amygdala (target pathway) during threat exposure. Maintenance of regulatory control was assessed after three days and in the absence of feedback. Training-induced changes in functional connectivity of the target pathway and anxiety ratings served as primary outcomes. Training of the target, yet not the sham-control, pathway significantly increased amygdala-vlPFC connectivity and decreased subjective anxiety levels. On the individual level stronger connectivity increases were significantly associated with anxiety reduction. At follow-up, volitional control over the target pathway and decreased anxiety level were maintained in the absence of feedback. The present results demonstrate for the first time that successful self-regulation of amygdala-prefrontal top-down regulatory circuits may represent a novel strategy to control anxiety. As such, the present findings underscore both the critical contribution of amygdala-prefrontal circuits to emotion regulation and the therapeutic potential of connectivity-informed real-time neurofeedback. 

[DeepL](www.DeepL.com/Translator) translation in French:

Une régulation émotionnelle déficiente et une anxiété exagérée représentent un marqueur psychopathologique transdiagnostic majeur. Au niveau neural, ces déficits ont été étroitement liés à un contrôle réglementaire préfrontal altéré, mais sensible au traitement, sur l'amygdale. Gagner un contrôle direct sur ces voies pourrait donc fournir une stratégie novatrice et prometteuse pour réguler l'anxiété exagérée. À cette fin, la présente étude de validation de concept a évalué la faisabilité, la pertinence fonctionnelle et le maintien d'une nouvelle formation en neurofeedback IRMf en temps réel et en tenant compte de la connectivité. Dans le cadre d'une conception aléatoire à l'intérieur du sujet, des sujets très anxieux (n = 26) ont suivi une formation guidée par IRMf en temps réel pour améliorer la connectivité entre le cortex préfrontal ventrolatéral (vlPFC) et l'amygdale (voie cible) pendant l'exposition à la menace.
