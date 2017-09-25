var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe("admin-notifications");

redis.on('message',function(channel,message){

    parsedMessage = JSON.parse(message)
    if(channel == "admin-notifications"){
        if(parsedMessage.event == "ReservationUpdation"){
            //Only emit to the relevant club using club id in the channel's name
            io.emit('admin-notifications:ReservationUpdation'+parsedMessage.data.club_id,true);
        }
        else if(parsedMessage.event == "RestaurantOrderUpdation"){
            //Only emit to the relevant club using club id in the channel's name
            io.emit('admin-notifications:RestaurantOrderUpdation'+parsedMessage.data.club_id,true);
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


