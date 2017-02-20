import sys
import json

import smtplib
from email.MIMEMultipart import MIMEMultipart
from email.MIMEText import MIMEText
import urllib

username = 'Your Username'
password = 'Your password'
fromaddr = 'Your from email'
ok = ''
websiteRoot = 'Your Website Root'

try:
    email = sys.argv[1]
    key = sys.argv[2]
    firstname = sys.argv[3]
    server = smtplib.SMTP('Your Server Address',ServerPort)
    server.starttls()
    server.login(username,password)
    msg = MIMEMultipart()
    msg['From'] = "FROM TEXT <FROM EMAIL>"
    msg['Subject'] = "Subject - New Subscriber"
    msg['To'] = email
    body = "Hi " + firstname + ",<br><br>Thank you so very much for subscribing to the newsletter. <br><br> I hope you'll find my projects interesting. <br><br> "
    body += "Please click the following link to confirm your subscription:<br><br><a href='"+websiteRoot+"?email="+urllib.quote(email)+"&key="+key+"'>CONFIRM MY SUBSCRIPTION</a><br><br>I hope you'll enjoy the new posts! <br> Have a lovely day!<br>Romain"
    body += "<br>If you don't know where that email comes from, just ignore it.<br><br><br>If, for a very strange reason, you wish to unsubscribe to the newsletter, follow this link:<br><a href='"+websiteRoot+"?email="+urllib.quote(email)+"&key="+key+"&unsub=yes'>UNSUBSCRIBE</a><br><br>For any question or anything, you can just reply to that email, I'll read it and answer it, true story!"
    msg.attach(MIMEText(body, 'html'))
    text = msg.as_string()
    server.sendmail(fromaddr, email, text)
    server.quit()
    ok = 'ok'
except ValueError:
    ok = ValueError
print ok