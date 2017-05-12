# Article API
A Restful API for authors and articles

## Setup
Download and install [Vagrant](https://www.vagrantup.com/downloads.html) then run

<pre>
<code>$ vagrant up</code>
</pre>
Available at
<pre>
<code>http://localhost:8080</code>
</pre>

## Run Unit Tests
From the host
<pre>
<code>vagrant ssh -c '/vagrant/scripts/runUnitTests.sh'</code>
</pre>

## API Docs
View at [localhost:8080/api/doc](http://localhost:8080/api/doc) or view API.md.

To rebuild the API.md file from host, simply run
<pre>
<code>vagrant ssh -c '/vagrant/scripts/buildAPIDocs.sh'</code>
</pre>

## Crontab
The server's crontab is setup to take what ever is in the file located at
<pre>./scripts/crontab</pre>

# Un-answered Questions
### To /api or not to /api
I took the approach to utilise /api and leave / as a HTML view with the intention to expose the Readme.md

# Reflections
### Deployable Environment
This Vagrant installation is not configured to be used in a production environment. It is designed for development purposes only

### Domain Driven Design
With a Domain mindset, I would opt to have an API Bundle and an Article Bundle. The APIBundle would be designed to handle requests, responses, serialisation and deserialisation whereas the ArticleBundle would house all the domain logic for authors and articles. The APIBundle would then consume ArticleBundle services to fulfil the request needs. I did start out doing this but started becoming a time sink so I abandoned the approach and opted for the unified AppBundle for expediency.