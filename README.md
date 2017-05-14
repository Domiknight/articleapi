# Article API
A Restful API for authors and articles as part of a coding challenge see Scope.md

## Setup
Download and install [Vagrant](https://www.vagrantup.com/downloads.html) then run
> This Vagrant installation is not configured to be used in a production environment. It is designed for development purposes only
<pre>
<code>$ vagrant up</code>
</pre>
Available at
<pre>
<code>http://localhost:8080</code>
<code>http://localhost:8080/api</code>
<code>http://localhost:8080/doc</code>
</pre>

## Run Unit Tests
From the host
<pre>
<code>vagrant ssh -c '/vagrant/scripts/runUnitTests.sh'</code>
</pre>

## API Docs
View at [http://localhost:8080/doc](http://localhost:8080/doc) or view API.md.

To rebuild the API.md file from host, simply run
<pre>
<code>vagrant ssh -c '/vagrant/scripts/buildAPIDocs.sh'</code>
</pre>

## Play with API calls in Postman
Get the shared [Collection](https://www.getpostman.com/collections/404bafd7843d322071a6)

## Crontab
The server's crontab is setup to take what ever is in the file located at
<pre>./scripts/crontab</pre>

# Un-answered Questions
### article.url
Given that article.url is persisted, I took the approach that the url is submitted as part of the payload and is likely to reference something outside the API.

# Assumptions
### To /api or not to /api
I took the approach to utilise /api for the actual API and /doc for the associated docs that way both of these can be bundled and served together.

# Reflections

### Domain Driven Design
With a Domain mindset, I would opt to have an API Bundle and an Article Bundle. The APIBundle would be responsible for handling requests, utilising services from other bundles, responses, serialisation and deserialisation exclusively. Whereas the ArticleBundle would house all the domain logic for authors and articles.

I did start out doing this but started becoming a time sink so I abandoned the approach and opted for the unified AppBundle for expediency.

### Testing Environment
I would improve the suite by configuring a specific environment for running tests

### Data Ownership
I took a product metadata approach to the implementation so I did not enforce any ownership validation e.g. author can not be changed
