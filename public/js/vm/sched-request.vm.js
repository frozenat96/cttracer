var ViewModels = ViewModels || {};
function NotificationsVM() {
    //cc

    //panel
    this.NotifyPanelOnSchedRequest = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyPanelOnRevisions = ko.observableArray([]).extend({ notify: 'always' });

    this.NotifyAdviserOnSchedRequest = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyAdviserOnRevisions = ko.observableArray([]).extend({ notify: 'always' });
    
    this.NotifyCoordOnProjFinalize = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyCoordOnReadyForStage = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyCoordOnSchedFinalize = ko.observableArray([]).extend({ notify: 'always' });
    //student

    this.AllNotifications = ko.computed(function () {
        var x = 0;
        let role = $('meta[name="role"]').attr('content');
        switch(role) {
            case '1': 
            x = x + this.NotifyCoordOnProjFinalize().length + this.NotifyCoordOnReadyForStage().length + this.NotifyCoordOnSchedFinalize().length;

            break;
            case '2': 
            x = x + this.NotifyPanelOnSchedRequest().length + this.NotifyPanelOnRevisions().length
            + this.NotifyAdviserOnSchedRequest().length + this.NotifyAdviserOnRevisions().length;
            break;
            case '3': 
            x = x + 1;
            break;
        }
        return x;
    }, this).extend({ notify: 'always' });
    

	this.callToServerForPanel = function () {
        self = this;
        axios.post('/NotifyPanelOnSchedRequest').then(response => {
            //self.schedRequest(response.data[0]);
            self.NotifyPanelOnSchedRequest(response.data);
            console.log(self.NotifyPanelOnSchedRequest());
        }).catch(error => {
            console.log(error.message);
        });
        
	}.bind(this);

}

$(document).ready(function () {
	ViewModels.Notifications = new NotificationsVM();
    ko.applyBindings(ViewModels.Notifications);
    let role = $('meta[name="role"]').attr('content');
    switch(role) {
        case '1': ViewModels.Notifications.callToServerForCoordinator();break;
        case '2': ViewModels.Notifications.callToServerForPanel();break;
        case '3': ViewModels.Notifications.callToServerForStudent();break;
    }
    //console.log("Customers", ViewModels.CustomerViewModel.Customers());
    Echo.private('notifications')
    .listen('eventTrigger', (e) => {
        let role = $('meta[name="role"]').attr('content');
        switch(role) {
            case '1': ViewModels.Notifications.callToServerForCoordinator();break;
            case '2': ViewModels.Notifications.callToServerForPanel();break;
            case '3': ViewModels.Notifications.callToServerForStudent();break;
        }
            
    });
});