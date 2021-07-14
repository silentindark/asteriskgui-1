
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
                    url: "../db/sip/channels",
                    data: filter,
                    success: function (data) {
                        json_data = data;  //store to global var for exporting

                        var counter = data.length;
                        // for (var i = 0; i < counter; i++) {
                        //     BridgedChannel = data[i].BridgedChannel.split('-')[0];
                        //     BridgedChannel = BridgedChannel.split('@')[0];
                        //     data[i].BridgedChannel = BridgedChannel;
                        // }
                        $("#total").text("Channels: " + counter);
                    },
                    complete: function () {
                        // Schedule the next request when the current one's complete
                        setTimeout($("#content").jsGrid("loadData"), 5000);
                    }
                });
            }
        },

        fields: [
            // { name: "channel", title: "Канал", type: "text", width: 100  },
            { name: "calleridname", title: "Имя", type: "text", width: 100 },
            { name: "calleridnum", title: "Номер", type: "text", width: 60 },
            // { name: "context", title: "Context", type: "text", width: 100 },
            // { name: "extension", title: "Extension", type: "text", width: 100 },
            { name: "duration", title: "Длительность", type: "text", width: 100 },
            { name: "channelstatedesc", title: "Состояние канала", type: "text", width: 50 },
            { name: "bridgedchannel", title: "Соединен с", type: "text", width: 100 },
            { name: "application", title: "Приложение", type: "text", width: 60 },
            { name: "applicationdata", title: "Данные из приложения", type: "text", width: 70 }
        ],
        onDataLoaded: function (data) {
        },
        onError: function () {
            console.log("Ошибка: " + this.src);
        }
    });
});
