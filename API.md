### `GET` /api/articles.{_format} ###

_Lists all article entities._

#### Requirements ####

**_format**

  - Requirement: json|xml|html


### `POST` /api/articles.{_format} ###

_Creates a new article entity._

#### Requirements ####

**_format**

  - Requirement: json|xml|html

#### Parameters ####

appbundle_article:

  * type: object (ArticleType)
  * required: true

appbundle_article[title]:

  * type: string
  * required: true

appbundle_article[url]:

  * type: string
  * required: true

appbundle_article[content]:

  * type: string
  * required: true

appbundle_article[author]:

  * type: choice
  * required: false

#### Response ####

id:

  * type: integer

title:

  * type: string

url:

  * type: string

content:

  * type: string


### `DELETE` /api/articles/{articleId}.{_format} ###

_Deletes a article entity._

#### Requirements ####

**_format**

  - Requirement: json|xml|html
**articleId**



### `GET` /api/articles/{id}.{_format} ###

_Gets the details for a specific article_

#### Requirements ####

**_format**

  - Requirement: json|xml|html
**id**


#### Response ####

id:

  * type: integer

title:

  * type: string

url:

  * type: string

content:

  * type: string


### `PUT` /api/articles/{id}.{_format} ###

_Displays a form to edit an existing article entity._

#### Requirements ####

**_format**

  - Requirement: json|xml|html
**id**


#### Parameters ####

appbundle_article:

  * type: object (ArticleType)
  * required: true

appbundle_article[title]:

  * type: string
  * required: true

appbundle_article[url]:

  * type: string
  * required: true

appbundle_article[content]:

  * type: string
  * required: true

appbundle_article[author]:

  * type: choice
  * required: false


### `POST` /api/authors.{_format} ###

_Create a new Author_

#### Requirements ####

**_format**

  - Requirement: json|xml|html

#### Parameters ####

appbundle_author:

  * type: object (AuthorType)
  * required: true

appbundle_author[name]:

  * type: string
  * required: true

#### Response ####

name:

  * type: string

id:

  * type: integer


### `GET` /api/authors/{authorId}.{_format} ###

_Get an Existing Author_

#### Requirements ####

**_format**

  - Requirement: json|xml|html
**authorId**
