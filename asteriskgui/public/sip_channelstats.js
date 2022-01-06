
$(function () {

    var json_data;

    $("#but_excel a").click(function () {
        JSONToCSVConvertor(json_data, "Registry", true);
    });
    DATA = null;

    $("#content").jsGrid({
        height: "93%",
        width: "100%",
        filtering: true,
        inserting: false,
        editing: false,
        sorting: true,
        paging: true,
        autoload: true,
        pageSize: 500,
        pageButtonCount: 5,
        deleteConfirm: "Do you really want to delete row?",
        controller: {
            loadData: function (filter) {
                return $.ajax({
                    type: "GET",
                    url: "/db/sip/channelstat/",
                    data: filter,
                    success: function (data) {
                        json_data = data;  //store to global var for exporting
                        $("#total").text("Channels: " + data.length);
                    },
                    complete: function () {
                        // Schedule the next request when the current one's complete
                        setTimeout($("#content").jsGrid("loadData"), 5000);
                    }
                });
            }
        },
        fields: [
            /*
            'peer' => $chan_info[0],
            'callid' => $chan_info[1],
            'duration' => $chan_info[2],
            'recv_pack' => $chan_info[3],
            'recv_lost' => $chan_info[4],
            'recv_lost_percent' => $chan_info[5],
            'recv_jitter' => $chan_info[6],
            'send_pack' => $chan_info[7],
            'send_lost' => $chan_info[8],
            'send_lost_percent' => $chan_info[9],
            'send_jitter' => $chan_info[10]
            */
            { name: "peer", title: "Peer", type: "text", width: 100 },
            { name: "callid", title: "CallerID", type: "text", width: 100 },
            { name: "duration", title: "Duration", type: "text", width: 100 },
            { name: "recv_pack", title: "Receive packets", type: "text", width: 100 },
            { name: "recv_lost", title: "Receive lost", type: "text", width: 100 },
            {
                name: "recv_lost_percent", itemTemplate: function (value) {
                    var color = "white";
                    if ((value > 0) & (value < 1)) {
                        color = 'yellow';
                    }
                    if (value >= '1') {
                        color = 'red';
                    }
                    return $("<div>").addClass("rating").css('background-color', color).append(value);
                }, title: "Receive lost %", type: "text", width: 60
            },
            {
                name: "recv_jitter", itemTemplate: function (value) {
                    var color = "white";
                    if ((value > 150) & (value < 300)) {
                        color = 'yellow';
                    }
                    if (value > '300') {
                        color = 'red';
                    }
                    return $("<div>").addClass("rating").css('background-color', color).append(value);
                }, title: "Receive jitter", type: "text", width: 70
            },
            { name: "send_pack", title: "Send packets", type: "text", width: 100 },
            { name: "send_lost", title: "Send lost", type: "text", width: 100 },
            {
                name: "send_lost_percent", itemTemplate: function (value) {
                    var color = "white";
                    if ((value > 0) & (value < 1)) {
                        color = 'yellow';
                    }
                    if (value >= '1') {
                        color = 'red';
                    }
                    return $("<div>").addClass("rating").css('background-color', color).append(value);
                }, title: "Send lost %", type: "text", width: 60
            },
            {
                name: "send_jitter", itemTemplate: function (value) {
                    var color = "white";
                    if ((value > 150) & (value < 300)) {
                        color = 'yellow';
                    }
                    if (value > '300') {
                        color = 'red';
                    }
                    return $("<div>").addClass("rating").css('background-color', color).append(value);
                }, title: "Send jitter", type: "text", width: 70
            }
        ],
        onDataLoaded: function (data) {
        },
        onError: function () {
            console.log("Ошибка: " + this.src);
        }

    });

});
