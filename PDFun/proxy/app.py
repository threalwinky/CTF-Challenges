import pycurl
from io import BytesIO
from urllib.parse import urlparse
from flask import Flask, request, jsonify
import os

app = Flask(__name__)

@app.get('/')
def index():
    return 'proxy'

@app.get('/proxy')
def proxy():
    url = request.args.get('url', 'https://example.com')
    if (urlparse(url).scheme == 'file'):
        return jsonify({'result': 'Not allowed protocol'})
    try:
        buffer = BytesIO()
        c = pycurl.Curl()
        c.setopt(c.URL, url)
        c.setopt(c.WRITEDATA, buffer)
        c.setopt(pycurl.CONNECTTIMEOUT, 5)
        c.setopt(pycurl.TIMEOUT, 10)
        c.perform()
        c.close()

        body = buffer.getvalue().decode('utf-8')
        return jsonify({'result': str(body)})
    except:
        return jsonify({'result': 'Failed'})

@app.get('/admin')
def admin():
    admin_username = os.getenv('ADMIN_USERNAME', 'REDACTED_USERNAME')
    admin_password = os.getenv('ADMIN_PASSWORD', 'REDACTED_PASSWORD')
    username = request.args.get('username', 'guest')
    password = request.args.get('password', 'guest')
    if (username == admin_username and password == admin_password):
        flag = open('/flag.txt').read()
        return flag
    return 'Not allowed'
    
app.run(host='0.0.0.0', port=5000, debug=False)