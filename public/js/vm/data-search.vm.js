var ViewModels = ViewModels || {};
function DataViewModel() {
	this.Data = ko.observableArray([]);
	this.totalNum = ko.observable();
	this.page = ko.observable(1);
	this.minPage = ko.observable();
	this.maxPage = ko.observable();
	this.query = ko.observable();

	this.pages = ko.computed(function () {
		var total = Math.ceil(this.totalNum() / 10);
		var arr = [], i=1;
		while(total--){
			arr.push(i);
			i++;
		}
		var min = this.page() - (this.page()%10);		
		if(this.page()%10==0) {
			min-=10;
		}
		var max = min +10;
		var arr = _.slice(arr, min, max);
		this.minPage = min;
		this.maxPage = max;
		return arr;
	}, this);

	//displayed Customers based on page.
	this.displayedCustomers = ko.computed(function () {
		var max = this.page() * 10;		
		var min = max -10;

		// this.Customers(_.slice(this.Customers(), min, max)); 
		//just return a new array instead of modifying Customers
		return slice(this.Customers(), min, max);
	}, this);

	this.goToPreviousPage = function () {
		if(this.page() > 1){
			var newPage = this.page() - 1;
			
			//this.callToServer();
			this.page(newPage);
			//this.totalNum(this.count);


		} 
	}.bind(this);

	this.goToPage = function (page) {	
		this.page(page);
	}.bind(this);

	this.jumpToPage = function (page) {	
		limit = ceil(this.totalNum() / 10);
		var input = document.getElementById ("pageSearch").value || "";
		if(input=="" || input < 1) {
			input = 1;
		}
		else if(input > limit) {
			input = limit;
		}

		this.goToPage(input);

	}.bind(this);

	this.goToFirstPage = function () {
		this.goToPage(1);
	}.bind(this);

	this.goToLastPage = function () {
		limit = ceil(this.totalNum() / 10);
		this.goToPage(limit);
	}.bind(this);

	this.goToNextPage = function () {
		if(this.page() < ceil(this.totalNum() / 10)){
			var newPage = this.page() + 1;
			//this.callToServer();
			this.page(newPage);
			//this.callToServer(newPage-(newPage%10),newPage-(newPage%10)+10,true);
			//this.totalNum(this.count);

		}
	}.bind(this);
	

	this.callToServer = function () {
		var self = this;
		//console.log("inside callToServer", self);
		var Query= {};
		Query.Search = document.getElementById ("search").value || "";

		var request = $.ajax({
		   url: '/project-search-r',
		   type: 'post',
		   data: {"search" : Query},
		});
		
		request.then(function(response) {
			//console.log(Customers);
			self.Data(response);
			self.totalNum(response.length);
			self.goToPage(1);
			if(self.totalNum == 0) {
				
			}
		});

	}.bind(this);

}

$(document).ready(function () {
	ViewModels.DataViewModel = new DataViewModel();
	ko.applyBindings(ViewModels.DataViewModel);
	//console.log("Customers", ViewModels.CustomerViewModel.Customers());
});