
var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redisSubscriber = new Redis();
var redis = new Redis();

console.log("Server Started...");

redisSubscriber.subscribe('projectUpdates');
redisSubscriber.on('message', function (channel, message) {
	message = JSON.parse(message);
	io.to(message.project).emit(message.updateType, message);
});

//when a socket is established, authenticate the socket then join them to the projects 'room'
io.on('connection', function(socket){
	socket.auth = false;
	socket.on('authenticate', function(key){
		redis.get(key, function(err, result){
			if (!err){
				var sessionData = JSON.parse(result);
				if (sessionData){
					socket.auth = true;
					console.log("Joining socket " + socket.id + " to project room: " + sessionData.project);
					socket.join(sessionData.project);
					io.to(sessionData.project).emit('userOnline', sessionData.user_id);
				}
			}
		});
			
	});
	setTimeout( function(){
    
	    //If the socket didn't authenticate, disconnect it
	    if (!socket.auth) {
	      console.log("Disconnecting socket ", socket.id);
	      socket.disconnect('Could not authenticate');
	    }
	 }, 1000);

	
	
});



server.listen(3000);
