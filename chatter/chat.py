import os
from flask_sqlalchemy import SQLAlchemy
from flask import Flask, request, session, url_for, redirect, render_template, abort, flash, json

app = Flask(__name__)
app.config.update(dict(
	DEBUG=True,
	SECRET_KEY='20661d4c333a42878e029c5ce5930244',
	SQLALCHEMY_DATABASE_URI = 'sqlite:///' + os.path.join(app.root_path, 'chat.db')
))
db = SQLAlchemy(app)

class User(db.Model):
    user_id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(64), nullable=False)
    password = db.Column(db.String(64), nullable=False)
    current_room = db.Column(db.String(64), db.ForeignKey('room.room_name'))

    def __init__(self, username, password, room):
        self.username = username
        self.password = password
        self.current_room = room

    def __repr__(self):
        return '{}'.format(self.username)


class Message(db.Model):
    message_id = db.Column(db.Integer, primary_key=True)
    message_text = db.Column(db.String(128), nullable=False)
    username = db.Column(db.String(64), nullable=False)
    room_id = db.Column(db.Integer, db.ForeignKey('room.room_id'))

    def __init__(self, message_text, username, room_id):
        self.message_text = message_text
        self.username = username
        self.room_id = room_id

    def __repr__(self):
        return '{}'.format(self.message_text)


class Room(db.Model):
    room_id = db.Column(db.Integer, primary_key=True)
    room_name = db.Column(db.String(64), nullable=False)
    messages = db.relationship('Message', backref='room', lazy='dynamic')
    username = db.Column(db.String(64), nullable=False)

    def __init__(self, room_name):
        self.room_name = room_name

    def __repr__(self):
        return '{}'.format(self.room_name)


@app.cli.command('initdb')
def initdb_command():
	db.create_all()
	db.session.commit()
	print('Database Initialized')

# clears all data from db and rebuilds it - used for testing purposes
@app.cli.command('cleardb')
def cleardb_command():
	db.drop_all()
	db.create_all()
	print('Database cleared')
 
# redirects if already signed in through session
@app.route('/')
def main():
    # routes to room list page if already logged in
	if session.get('logged_in'):
		return redirect(url_for('room_list'))
	# otherwise routes to login page
	return redirect(url_for('login'))

# checks if user has attemped to log in through post and validates their credentials if so
@app.route('/login', methods=['GET', 'POST'])
def login():
	if request.method == 'POST':
		users = User.query.all()
		for user in users:
			if user.username == request.form['username'] and user.password == request.form['password']:
				session['logged_in'] = True
				session['user_id'] = user.user_id
				session['username'] = user.username
				user.current_room = None
				db.session.commit()
				return redirect(url_for('room_list'))
				
		flash('⚠ Incorrect username or password', 'flashError')

	elif session.get('logged_in'):
			return redirect(url_for('room_list'))
				
	return render_template('login.html')

# logs user out and redirects to login page
@app.route('/logout')
def logout():
	if session.get("logged_in"):
		user = User.query.filter_by(user_id=session['user_id']).first()
		user.current_room = None
		db.session.commit()
		session['logged_in'] = False
		session['user_id'] = None
		session['username'] = None
		flash('✓ Logged out successfully', 'flash')
	return redirect(url_for('login'))

# queries all chatrooms and renders template for rooms page
@app.route('/room_list_<left_room>', methods=['GET', 'POST'])
@app.route('/room_list', methods=['GET', 'POST'])
def room_list(left_room = False):
	if not session.get('logged_in'):
		return redirect(url_for('login'))

	session['room_id'] = None
	if left_room:
		user = User.query.filter_by(user_id=session['user_id']).first()
		user.current_room = None
		db.session.commit()
	rooms = Room.query.all()
	return render_template('rooms.html', chatrooms=rooms, username=session['username'])

# renders template for a room based on a room_id passed into it
@app.route('/room_<room_id>', methods=['GET', 'POST'])
def room(room_id):
	if not session.get('logged_in'):
		return redirect(url_for('login'))
	user = User.query.filter_by(user_id=session['user_id']).first()
	room = Room.query.filter_by(room_id=room_id).first()
	if user.current_room != None and user.current_room != room.room_name:
		flash('⚠ Cannot be in multiple rooms at once', 'flashError')
		return redirect(url_for('room_list'))

	session['room_id'] = room_id
	user.current_room = room.room_name
	db.session.commit()
	return render_template('room.html', chatroom=room, username=session['username'])

# checks if a user with the passed username already exists
# and, if not, creates a new user
@app.route('/create_user', methods=['POST'])
def create_user():
	if request.method == 'POST':
		dupe_user = User.query.filter_by(username=request.form['new_username']).first()
		if dupe_user is None:
			new_user = User(request.form['new_username'], request.form['new_password'], None)
			db.session.add(new_user)
			db.session.commit()
			flash('✓ Account created!', 'flash')
		else:
			flash('⚠ Account with that username already exists', 'flashError')
	else:
		flash('⚠ Error creating user', 'flashError')

	return redirect(url_for('login'))

# checks if chatroom with passed name already exists
# and, if not, creates a new room
@app.route('/create_room', methods=['POST'])
def create_room():
	if not session.get('logged_in'):
		return redirect(url_for('login'))
	if request.form['roomname'] == "" or request.form['roomname'].isspace():
		flash('⚠ Chatroom name cannot be blank', 'flashError')
		return redirect(url_for('room_list'))
 
	new_room = Room(request.form['roomname'])
	new_room.username = session['username']
	all_rooms = Room.query.all()
	for room in all_rooms:
		if room.room_name == new_room.room_name:
			flash('⚠ Chatroom with that name already exists!', 'flashError')
			return redirect(url_for('room_list'))
	db.session.add(new_room)
	db.session.commit()
	return redirect(url_for('room_list'))

# first checks if current user owns the room to be deleted
# if they do, delete the room and all messages in it
@app.route('/delete_room_<room_id>')
def delete_room(room_id):
	if not session.get('logged_in'):
		return redirect(url_for('login'))

	room_to_del = Room.query.filter_by(room_id=room_id).first()
	if room_to_del is not None:
		if room_to_del.username == session['username']:
			#Delete all messages affilated with that room
			msgs = Message.query.all()
			for i in msgs:
				if i.room_id == room_id:
					db.session.delete(i)
			db.session.delete(room_to_del)
			db.session.commit()
		else:
			flash('⚠ Not authorized to delete this room', 'flashError')
	else:
		flash('⚠ Failed to delete chatroom', 'flashError')
	return redirect(url_for('room_list'))

# adds a new message to the current room in the db
# and returns a json of the message
@app.route("/new_message", methods=["POST"])
def new_message():
    text = request.form["message"]
    room_id = session['room_id']
    user_id = session['user_id']

    room = Room.query.filter_by(room_id=room_id).first()
    user = User.query.filter_by(user_id=user_id).first()

    message = Message(text, user.username, room_id)
    room.messages.append(message)
    db.session.add(message)
    db.session.commit()

    list = {user.username: text}
    return json.dumps(list)

# first checks if the room has been deleted and sends an explicit message if so
# otherwise, queries all messages for the current room and returns a json list of them
@app.route("/messages")
def get_messages():
	message_list = {}
	if Room.query.filter_by(room_id=session['room_id']).first() is None:
		message_list[0] = "room is now empty"
	else:
		messages = Message.query.filter_by(room_id=session['room_id']).all()
		index = 0
		for m in messages:
			new_message = {m.username: m.message_text}
			message_list[index] = new_message
			index = index + 1
	return json.dumps(message_list)


if __name__ == "__main__":
    app.run(threaded=True)