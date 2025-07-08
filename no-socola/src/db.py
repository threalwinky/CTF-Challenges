import os
from pymongo import MongoClient

def get_database():
    """Initialize and return MongoDB database connection"""
    mongo_host = os.environ.get('MONGO_HOST', 'localhost')
    client = MongoClient(f'mongodb://{mongo_host}:27017/')
    
    try:
        client.drop_database('socola_db')
    except Exception:
        pass
    
    return client['socola_db']

def get_sample_data():
    """Return sample data for users and chocolates"""
    FLAG = os.environ.get('FLAG', 'Flag{default_flag}')
    ADMIN_PASSWORD = os.environ.get('ADMIN_PASSWORD', 'default_admin_password')
    
    users = [
        {'username': 'user1', 'password': 'password1', 'role': 'user'},
        {'username': 'user2', 'password': 'password2', 'role': 'user'},
        {'username': 'user3', 'password': 'password3', 'role': 'user'},
        {'username': 'admin123', 'password': 'admin_is_here_hehe', 'role': 'user'},
        {'username': 'admin', 'password': ADMIN_PASSWORD, 'role': 'admin'},
        {'username': 'admin456', 'password': 'this_is_admin_password', 'role': 'user'}
    ]

    socolas = [
        {
            'name': 'Dairy Milk Chocolate',
            'role': 'user',
            'img': 'https://product.hstatic.net/1000141988/product/socola_sua_cadbury_dairy_milk_160_g__i0015956__8938d9c63c194e1280c4c1d6327cf765_master.png',
            'FLAG': 'Flag{this_is_a_fake_flag}'
        },
        {
            'name': 'Milk Chocolate',
            'role': 'user',
            'img': 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnPYd6Owh2LHtSxhe99k91UJNsHzRw79Ymxg&s',
            'FLAG': 'Flag{this_is_a_fake_flag}'
        },
        {
            'name': 'Chocolate Bar',
            'role': 'user',
            'img': 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRCK__lPOf5fvpi68vfxSOcfn5KPnoPcaXarQ&s',
            'FLAG': 'Flag{this_is_a_fake_flag}'
        },
        {
            'name': 'Hershey\'s Chocolate',
            'role': 'user',
            'img': 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQfU54vk5pt65CPBxIlfGsSamCowV08Y7TDdg&s',
            'FLAG': 'Flag{this_is_a_fake_flag}'
        },
        {
            'name': 'Chocolate Box',
            'role': 'user',
            'img': 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTTOkht39I5wGs1W3kj00ZT3DkG-mbGZTm3TQ&s',
            'FLAG': 'Flag{this_is_a_fake_flag}'
        },
        {
            'name': 'Snickers Chocolate',
            'role': 'admin',
            'img': 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTYo7dY0bHKZk6TASx0vGPmSBwW54JnuJ8yWQ&s',
            'FLAG': FLAG
        },
        {
            'name': 'Almond Chocolate',
            'role': 'user',
            'img': 'https://www.lottemart.vn/media/catalog/product/cache/0x0/8/8/8801062634354-1.jpg.webp',
            'FLAG': 'Flag{this_is_a_fake_flag}'
        }
    ]
    
    return users, socolas

def initialize_database(db):
    """Initialize database with sample data"""
    users, socolas = get_sample_data()
    
    for user in users:
        db.users.insert_one({
            'username': user['username'],
            'password': user['password'],
            'role': user['role']
        })

    for socola in socolas:
        db.socolas.insert_one({
            'name': socola['name'],
            'role': socola['role'],
            'img': socola['img'],
            'FLAG': socola['FLAG']
        })
