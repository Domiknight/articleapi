# Article API
A Restful API for authors and articles

## Setup
Download and install [Vagrant](https://www.vagrantup.com/downloads.html) then run

<pre>
<code>$ vagrant up</code>
</pre>
Available at
<pre>
<code>http://localhost:8080/api</code>
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
It wasn't clear to me to persist this information. The example makes it look like it is a resource url but with RESTful, you don't need to persist this information. If this is the case, then I would opt for utilising the Location header to communicate this information. If it actually submitted data, then shouldn't the submitted value be a complete URI not just the URL?
 
I took the approach of using Location Headers and treated the url field as some submitted value that needed to sluggified

# Assumptions
### To /api or not to /api
I took the approach to utilise /api for the actual API and /doc for the associated docs that way both of these can be bundled and served together.

# Reflections
### Deployable Environment
This Vagrant installation is not configured to be used in a production environment. It is designed for development purposes only. Let's chat about deployment processes

### Domain Driven Design
With a Domain mindset, I would opt to have an API Bundle and an Article Bundle. The APIBundle would be responsible for handling requests, responses, serialisation and deserialisation exclusively. Whereas the ArticleBundle would house all the domain logic for authors and articles. The APIBundle would then consume ArticleBundle services to fulfil the request needs. I did start out doing this but started becoming a time sink so I abandoned the approach and opted for the unified AppBundle for expediency.

### Testing Environment
I would configure the test suite to use a specific environment for running functional tests

### Data Ownership
I took a product metadata approach to the implementation so I did not enforce any ownership validation e.g. author can not be changed 
