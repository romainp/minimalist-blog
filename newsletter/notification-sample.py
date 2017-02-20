# Remove -sample before use
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
    subscriber = sys.argv[1]
    status = sys.argv[2]
    email = 'Your Email'
    server = smtplib.SMTP('Your Server Address',ServerPort)
    server.starttls()
    server.login(username,password)
    msg = MIMEMultipart()
    msg['From'] = "FROM TEXT <FROM EMAIL>"
    msg['Subject'] = "Subject - New Subscriber"
    msg['To'] = email
    if (status == 'sub'):
        body = "Yo<br>"+subscriber+" just subscribed. BOOM!"
    else:
        body = "That hurts, "+subscriber+" just left."
    msg.attach(MIMEText(body, 'html'))
    text = msg.as_string()
    server.sendmail(fromaddr, email, text)
    server.quit()
    ok = 'ok'
except ValueError:
    ok = ValueError
print ok
