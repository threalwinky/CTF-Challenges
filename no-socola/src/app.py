from flask import Flask, render_template, request, session, redirect
import json
import os
from db import get_database, initialize_database

app = Flask(__name__)
app.config['SECRET_KEY'] = os.environ.get('SECRET_KEY', 'default_secret_key')
ADMIN_PASSWORD = os.environ.get('ADMIN_PASSWORD', 'default_admin_password')

db = get_database()
initialize_database(db)

def is_admin_blocked(username, password):
    """Check if admin keyword is present in credentials"""
    return 'admin' in json.dumps(username) or ADMIN_PASSWORD in json.dumps(password)

def authenticate_user(username, password):
    """Authenticate user against database"""
    return db.users.find_one({'username': username, 'password': password})

def is_authenticated():
    """Check if user is authenticated"""
    return 'username' in session and 'role' in session

def get_user_socolas(role):
    """Get socolas based on user role"""
    if role == 'admin':
        return list(db.socolas.find())
    return list(db.socolas.find({'role': role}))

def search_socolas(search_data, user_role):
    """Search socolas with user-provided criteria"""
    search_data['role'] = user_role
    return list(db.socolas.find(search_data))

def format_socola_response(socola_list):
    """Format socola data for response - hide FLAG field for blind injection challenge"""
    return [{'name': socola['name'], 'img': socola['img'], 'role': socola.get('role', 'user')} for socola in socola_list]

@app.route('/', methods=['GET', 'POST'])
def index():
    if request.method == 'POST':
        try:
            data = request.get_json()
            if not data:
                return {"message": "Invalid request format"}, 400
                
            username = data.get('username')
            password = data.get('password')
            
            if not username or not password:
                return {"message": "Username and password are required"}, 400
            
            if is_admin_blocked(username, password):
                return {"message": "Invalid credentials"}, 401
                
            user = authenticate_user(username, password)
            if not user:
                return {"message": "Invalid credentials"}, 401
                
            session['username'] = user['username']
            session['role'] = user['role']
            return {"message": "Login successful"}, 200
            
        except Exception:
            return {"message": "Invalid request format"}, 400
        
    return render_template('index.html')

@app.route('/socola', methods=['GET', 'POST'])
def socola():
    if not is_authenticated():
        return redirect('/')
        
    user_role = session['role']
    
    if request.method == 'POST':
        try:
            data = request.get_json()
            if not data:
                return {"message": "Invalid request format"}, 400
                
            socola_list = search_socolas(data, user_role)
            formatted_socolas = format_socola_response(socola_list)
            return {"socolas": formatted_socolas}, 200
            
        except Exception:
            return {"message": "Search failed"}, 400
    
    socola_list = get_user_socolas(user_role)
    formatted_socolas = format_socola_response(socola_list)
    return render_template('socola.html', socolas=formatted_socolas)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)