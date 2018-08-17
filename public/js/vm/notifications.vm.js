var ViewModels = ViewModels || {};
function NotificationsVM() {

    this.NotifyPanelOnSchedRequest = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyPanelOnProjectApproval = ko.observableArray([]).extend({ notify: 'always' }); 
    this.NotifyAdviserOnSubmission = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyAllOnReady = ko.observableArray([]).extend({ notify: 'always' });

    this.NotifyCoordOnSchedRequest = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyCoordOnNextStage = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyCoordOnSchedFinalize = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyCoordOnProjectArchive = ko.observableArray([]).extend({ notify: 'always' });
    
    this.NotifyStudentOnAdvCorrected = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyStudentOnCompletion = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyStudentOnPanelCorrected = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyStudentOnFinish = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyStudentOnSchedDisapproved = ko.observableArray([]).extend({ notify: 'always' });
    this.NotifyStudentOnNextStage = ko.observableArray([]).extend({ notify: 'always' });

    this.AllNotifications = ko.computed(function () {
        var x = 0;
        let role = $('meta[name="role"]').attr('content');
        switch(role) {
            case '1': 
            x = x + this.NotifyCoordOnNextStage().length +
            this.NotifyCoordOnSchedFinalize().length +
            this.NotifyCoordOnSchedRequest().length +
            this.NotifyPanelOnSchedRequest().length +
            this.NotifyPanelOnProjectApproval().length +
            this.NotifyAdviserOnSubmission().length +
            this.NotifyCoordOnProjectArchive().length;
            break;
            case '2': 
            x = this.NotifyPanelOnSchedRequest().length +
            this.NotifyPanelOnProjectApproval().length +
            this.NotifyAdviserOnSubmission().length +
            this.NotifyAllOnReady().length;
            break;
            case '3': 
            x = this.NotifyStudentOnAdvCorrected().length +
            this.NotifyStudentOnNextStage().length +
            this.NotifyStudentOnCompletion().length +
            this.NotifyStudentOnPanelCorrected().length + 
            this.NotifyStudentOnFinish().length;
            this.NotifyStudentOnSchedDisapproved().length+
            this.NotifyAllOnReady().length;
            break;
        }
        return x;
    }, this).extend({ notify: 'always' });
    

	this.callToServerForPanel = function () {
        self = this;
        axios.post('/n/NotifyAdviserOnSubmission').then(response => {
            if(response) {
            self.NotifyAdviserOnSubmission(response.data);
            }
        });
        axios.post('/n/NotifyPanelOnSchedRequest').then(response => {
            if(response) {
            self.NotifyPanelOnSchedRequest(response.data);
            }
        });
        axios.post('/n/NotifyPanelOnProjectApproval').then(response => {
            if(response) {
            self.NotifyPanelOnProjectApproval(response.data);
            }
        });
        axios.post('/n/NotifyAllOnReady').then(response => {
            if(response) {
            self.NotifyAllOnReady(response.data);
            }
        });
        
    }.bind(this);
    
    this.callToServerForCoordinator = function () {
        self = this;
        axios.post('/n/NotifyCoordOnSchedRequest').then(response => {
            if(response) {
                self.NotifyCoordOnSchedRequest(response.data);
            } 
        });
        axios.post('/n/NotifyCoordOnSchedFinalize').then(response => {
            if(response) {
            self.NotifyCoordOnSchedFinalize(response.data);
            }
        });
        axios.post('/n/NotifyCoordOnNextStage').then(response => {
            if(response) {
            self.NotifyCoordOnNextStage(response.data);
            }
        });
        axios.post('/n/NotifyAdviserOnSubmission').then(response => {
            if(response) {
            self.NotifyAdviserOnSubmission(response.data);
            }
        });
        axios.post('/n/NotifyPanelOnSchedRequest').then(response => {
            if(response) {
            self.NotifyPanelOnSchedRequest(response.data);
            }
        });
        axios.post('/n/NotifyPanelOnProjectApproval').then(response => {
            if(response) {
            self.NotifyPanelOnProjectApproval(response.data);
            }
        });
        axios.post('/n/NotifyCoordOnProjectArchive').then(response => {
            if(response) {
            self.NotifyCoordOnProjectArchive(response.data);
            }
        });
        
	}.bind(this);

    this.callToServerForStudent = function () {
        self = this;
        axios.post('/n/NotifyStudentOnAdvCorrected').then(response => {
            if(response) {
            self.NotifyStudentOnAdvCorrected(response.data);
            }
        });
        axios.post('/n/NotifyStudentOnPanelCorrected').then(response => {
            if(response) {
            self.NotifyStudentOnPanelCorrected(response.data);
            }
        });
        axios.post('/n/NotifyStudentOnSchedDisapproved').then(response => {
            if(response) {
            self.NotifyStudentOnSchedDisapproved(response.data);
            }
        });
        axios.post('/n/NotifyStudentOnNextStage').then(response => {
            if(response) {
            self.NotifyStudentOnNextStage(response.data);
            }
        });
        axios.post('/n/NotifyStudentOnCompletion').then(response => {
            if(response) {
            self.NotifyStudentOnCompletion(response.data);
            }
        });
        axios.post('/n/NotifyStudentOnFinish').then(response => {
            if(response) {
            self.NotifyStudentOnFinish(response.data);
            }
        });
        axios.post('/n/NotifyAllOnReady').then(response => {
            if(response) {
            self.NotifyAllOnReady(response.data);
            }
        });
        
	}.bind(this);


}

$(document).ready(function () {
    $('notificationMenuList1').hide();
	ViewModels.Notifications = new NotificationsVM();
    ko.applyBindings(ViewModels.Notifications);
    let role = $('meta[name="role"]').attr('content');
    switch(role) {
        case '1': ViewModels.Notifications.callToServerForCoordinator();break;
        case '2': ViewModels.Notifications.callToServerForPanel();break;
        case '3': ViewModels.Notifications.callToServerForStudent();break;
    }
    Echo.private('notifications')
    .listen('eventTrigger', (e) => {
        let role = $('meta[name="role"]').attr('content');
        switch(role) {
            case '1': ViewModels.Notifications.callToServerForCoordinator();break;
            case '2': ViewModels.Notifications.callToServerForPanel();break;
            case '3': ViewModels.Notifications.callToServerForStudent();break;
        }
            
    });
    $('notificationMenuList1').show();
});