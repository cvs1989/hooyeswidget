import cgi
import os
from google.appengine.api import users
from google.appengine.ext import webapp
from google.appengine.ext.webapp.util import run_wsgi_app
from google.appengine.ext.webapp import template
from google.appengine.ext import db

class Greeting(db.Model):
  author = db.UserProperty()
  content = db.StringProperty(multiline=True)
  date = db.DateTimeProperty(auto_now_add=True)

class MainPage(webapp.RequestHandler):
  def get(self):
   if users.get_current_user():
    url = users.create_logout_url(self.request.uri)
    url_linktext = 'Logout'
   else:
    url = users.create_login_url(self.request.uri)
    url_linktext = 'Login'
   user = users.get_current_user()
   #self.response.out.write("Welcome, %s!" % user.nickname())
   if users.is_current_user_admin():
	self.response.out.write("Welcome, %s!" % user.nickname())
	greetings_query = Greeting.all().order('-date')
	greetings = greetings_query.fetch(100)
	template_values = {
	  'greetings': greetings,
	  'url': url,
	  'url_linktext': url_linktext
	  }
	path = os.path.join(os.path.dirname(__file__), 'index.html')
	self.response.out.write(template.render(path, template_values))
   else:
	template_values2={
	  'url': url,
	  'url_linktext': url_linktext
	 }
	path = os.path.join(os.path.dirname(__file__), 'NoAccess.html')
	self.response.out.write(template.render(path, template_values2))

class Guestbook(webapp.RequestHandler):
  def post(self):
    greeting = Greeting()

    if users.get_current_user():
      greeting.author = users.get_current_user()

    greeting.content = self.request.get('content')
    greeting.put()
    self.redirect('/')
class Api(webapp.RequestHandler):
  def post(self):
    greeting = Greeting()
    #greeting.author = User(email=hooyes@gmail.com)
    greeting.content = self.request.get('content')
    greeting.put()
    self.response.out.write('ok')
class Del(webapp.RequestHandler):
   def get(self):
	   key1=self.request.get('key')
	   q = db.GqlQuery("SELECT * FROM Greeting WHERE __key__  = :1", db.Key(key1))
	   results = q.fetch(1)
	   db.delete(results)
	   self.redirect('/')

application = webapp.WSGIApplication(
                                     [('/', MainPage),
                                      ('/sign', Guestbook),
                                      ('/api',Api),
                                      ('/del.aspx',Del)],
                                     debug=True)

def main():
  run_wsgi_app(application)

if __name__ == "__main__":
  main()