var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe("admin-notifications");

redis.on('message',function(channel,message){

    parsedMessage = JSON.parse(message)
    if(channel == "admin-notifications"){
        if(parsedMessage.event == "ReservationUpdation"){
            console.log(message);
            io.emit('admin-notifications:ReservationUpdation',true);
        }
    }
    
    
    
});
// io.on('connection',function(socket){
//     //console.log("Connection Made");
//
//
//
// });


server.listen(3000);


