$(function() {
	try { 
		$.ajax({
			url: 'data',
			type: 'GET',
			success: function(result) {

				try {

					//console.log('result: ' + result);

					var myObj = JSON.parse(result);
					var cartObj = JSON.parse(result);
					/*
					if (myObj.errors) {
						vm.errors.errorCode = myObj.errors.errorCode;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						return;
					}*/

		    		vm.albums = myObj;
		    		Vue.set(vm.customer, 'cart', myObj);
		    		//vm.customer.cart = cartObj;
		    		for (i in vm.customer.cart) {
		    			Vue.set(vm.customer.cart[i], 'quantity', 1);
		    			Vue.set(vm.customer.cart[i], 'calPrice', vm.customer.cart[i].price * vm.customer.cart[i].quantity);
		    			//vm.customer.cart[i].calPrice = vm.customer.cart[i].price * vm.customer.cart[i].quantity;
		    		}
		    		//set history state for homepage
		    		history.replaceState({ appSettings: vm.appSettings, albums: vm.albums, artists: vm.artists }, '');
	    		} catch (error) {
					vm.errors.errorCode = error.name;
					vm.appSettings.pageTitle = 'error';
					vm.appSettings.leftPanelView = 'none';
					vm.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			},
			error: function(error) {
				vm.errors.errorCode = error.name;
				vm.appSettings.pageTitle = 'error';
				vm.appSettings.leftPanelView = 'none';
				vm.appSettings.rightPanelView = 'errors';
				console.log(error.message);
				return;
			}
		});
	} catch (error) {
		vm.errors.errorCode = error.name;
		vm.appSettings.pageTitle = 'error';
		vm.appSettings.leftPanelView = 'none';
		vm.appSettings.rightPanelView = 'errors';
		console.log(error.message);
		return;
	}
});