
Vue.component('left-album-covers', {
	props: ['albums', 'appSettings'],
	methods: {
		toggleInfo: function() {
			vm.appSettings.showInfo = !vm.appSettings.showInfo;
		}
	}
});

Vue.component('left-user-menu', {
	props: ['customer'],
	methods: {
		loadUserCart: function() {
			vm.appSettings.customerAccountPage = 'cart';
			vm.appSettings.pageTitle = 'my cart';
			history.pushState({ appSettings: this.appSettings }, '');
		},
		loadUserOrders: function() {
			vm.appSettings.customerAccountPage = 'orders';
			vm.appSettings.pageTitle = 'my orders';
			history.pushState({ appSettings: this.appSettings }, '');
		},
		loadEditUser: function() {
			resetFormErrors();
			vm.appSettings.customerAccountPage = 'editCustomer';
			vm.appSettings.pageTitle = 'edit account';
			vm.success.success = false;
			history.pushState({ appSettings: this.appSettings }, '');
		},
		loadCustomerLogin: function() {
			resetFormErrors();
			vm.appSettings.showRightLogo = false;
			vm.appSettings.showLeftLogo = false;
			vm.appSettings.showInfo = false;
			vm.appSettings.showNav = false;

			vm.appSettings.pageTitle = 'User Login';
			vm.appSettings.leftPanelView = 'none';
			vm.appSettings.rightPanelView = 'customerLogin';

			vm.errorsExist = false;
			vm.appSettings.loginForm = 'customerLogin';
			vm.errors.formErrors.password = false;
			vm.errors.formErrors.email = false;
			vm.errors.formErrors.firstName = false;
			vm.errors.formErrors.lastName = false;
			history.pushState({ appSettings: this.appSettings }, '');
		},
		logout: function() {
			resetFormErrors();
			vm.customer.details = {};
			vm.customer.loggedIn = false;
			vm.appSettings.showRightLogo = false;
			vm.appSettings.showLeftLogo = false;
			vm.appSettings.showInfo = false;
			vm.appSettings.showNav = false;
			vm.appSettings.pageTitle = 'User Login';
			vm.appSettings.leftPanelView = 'none';
			vm.appSettings.rightPanelView = 'customerLogin';
			vm.appSettings.loginForm = 'customerLogin';
		}
	}
});

Vue.component('right-album-info', {
	props: ['albums', 'appSettings', 'formattedPrice'],
	methods: {
		toggleInfo: function() {
			vm.appSettings.showInfo = !vm.appSettings.showInfo;
		},
		addToCart: function() {
			var albumExists = vm.customer.cart.filter(val => { return val.albumID == vm.albums[vm.appSettings.currentAlbum].albumID });
			console.log('albumExists: ' + albumExists);
			//if album is already in cart add to album quantity. Otherwise add album to cart
			if (albumExists.length) {
				console.log('Album Exists!');
				vm.customer.cart.filter(val => {
					if (val.albumID == vm.albums[vm.appSettings.currentAlbum].albumID) {
						val.quantity++
					}
				});
			} else {
				var album = vm.albums.slice(vm.appSettings.currentAlbum, vm.appSettings.currentAlbum + 1);
				vm.customer.cart.push(album[0]);
				Vue.set(vm.customer.cart[vm.customer.cart.length - 1], 'quantity', 1);
    			Vue.set(vm.customer.cart[vm.customer.cart.length - 1], 'calPrice', vm.customer.cart[vm.customer.cart.length - 1].price * vm.customer.cart[vm.customer.cart.length - 1].quantity);
				//album[0].quantity = 1;
				//album[0].calPrice = album[0].price & album[0].quantity;
				//Vue.set(vm.customer.cart, vm.customer.cart.length, album[0]);
			}
		}
	}
});

Vue.component('right-artists-list', {
	props: ['artists', 'pageTitle'],
	computed: {
		artistsFirstColmn: function() {
			return this.artists.slice(0, (Math.ceil(this.artists.length/2)));
		},
		artistsSecondColmn: function() {
			return this.artists.slice((Math.ceil(this.artists.length/2)), this.artists.length);
		}
	},
	methods: {
		
	}
});

Vue.component('right-albums-list', {
	props: ['albums', 'pageTitle'],
	computed: {
		albumsFirstColmn: function() {
			return this.albums.slice(0, (Math.ceil(this.albums.length/2)));
		},
		albumsSecondColmn: function() {
			return this.albums.slice((Math.ceil(this.albums.length/2)), this.albums.length);
		}
	},
	methods: {

	}
});

Vue.component('right-customer-login', {
	props: ['appSettings', 'customer', 'errors'],
	methods: {
		submitLogin: function() {
			resetFormErrors();
			var email = $('#login-email').val();
			var password = $('#login-password').val();
			console.log('email: ' + email);
			console.log('password: ' + password);

			vm.appSettings.loading = true;
			$.ajax({
				url: 'data/index.php',
				type: 'POST',
				data: { 
					action: 'userLogin', 
					email: email, 
					password: password 
				},
				success: (result) => {
					try {
						console.log('result: ' + result);
						var result = JSON.parse(result);
						if (!result.incorrectLogin) {
							console.log(result);
							vm.customer.details = result.details;
							//Vue.set(vm.customer, 'details', result)
							vm.appSettings.loading = false;
							if (result.orders) { console.log(result.orders); vm.customer.orders = result.orders; }
							vm.customer.loggedIn = true;
							vm.appSettings.rightPanelView = 'customerAccount';
							vm.appSettings.customerAccountPage = 'welcome';
							vm.appSettings.leftPanelView = 'userMenu';
							vm.appSettings.pageTitle = 'User Account';
						} else {
							vm.customer.incorrectLogin = true;
						}

						console.log(result);
					} catch (error) {
						vm.appSettings.loading = false;
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
					vm.appSettings.loading = false;
					vm.errors.errorCode = error.name;
					vm.appSettings.pageTitle = 'error';
					vm.appSettings.leftPanelView = 'none';
					vm.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			});
		},
		showRegisterForm: function() {
			resetFormErrors();
			vm.appSettings.loginForm = 'createCustomer';
			vm.appSettings.pageTitle = 'Register User';
			history.pushState({ appSettings: vm.appSettings }, '');
		},
		createCustomer: function() {
			resetFormErrors();
			var firstName = $('#first-name').val();
			var lastName = $('#last-name').val();
			var email = $('#create-login-email').val();
			var password = $('#create-login-password').val();
			var confirmPassword = $('#create-confirm-password').val();

			console.log('firstName: ' + firstName);

			//validation checks
			var firstNameValid = validate('name', firstName, null, 60, 1, true);
			var lastNameValid = validate('name', lastName, null, 60, 1, true);
			var emailValid = validate('email', email, null, 255, 3, true);
			var passwordValid = validate('password', password, confirmPassword, 20, 5, true);

			if (firstNameValid !== true) {
				vm.errors.formErrors.firstName = true;
				vm.errors.formErrors.firstNameMessage = firstNameValid;
			}

			if (lastNameValid !== true) {
				vm.errors.formErrors.lastName = true;
				vm.errors.formErrors.lastNameMessage = lastNameValid;
			}

			if (emailValid !== true) {
				vm.errors.formErrors.email = true;
				vm.errors.formErrors.emailMessage = emailValid;
			}

			if (passwordValid !== true) {
				vm.errors.formErrors.password = true;
				vm.errors.formErrors.passwordMessage = passwordValid;
			}

			// if any errors exist, exit function
			for (var key in vm.errors.formErrors) {
				if (vm.errors.formErrors[key]) {
					console.log('errors test');
					return;
				}
			}


			vm.appSettings.loading = true;
			$.ajax({
				url: 'data/index.php',
				type: 'POST',
				data: { 
					action: 'checkUserExists', 
					email: email 
				},
				success: (result) => {
					try {
						console.log('result: ' + result);
						if (result) {
							vm.appSettings.loading = false;
							vm.errors.formErrors.email = true;
							vm.errors.formErrors.emailMessage = 'This email is already in use.';
							return;
						} 

						$.ajax({
							url: 'data/index.php',
							type: 'POST',
							data: { 
								action: 'addUser',
								firstName: firstName,
								lastName: lastName,
								email: email,
								password: password
							},
							success: () => {
								try {
									$.ajax({
										url: 'data/index.php',
										type: 'POST',
										data: { 
											action: 'userLogin', 
											email: email, 
											password: password 
										},
										success: (result) => {
											try {
												vm.appSettings.loading = false;
												var result = JSON.parse(result);
												if (!result.incorrectLogin) {
													console.log(result);
													vm.customer.loggedIn = true;
													vm.customer.details = result;
													vm.appSettings.rightPanelView = 'customerAccount';
													vm.appSettings.customerAccountPage = 'welcome';
													vm.appSettings.pageTitle = 'User Account';
												} else {
													vm.customer.incorrectLogin = true;
												}

												console.log(result);
											} catch (error) {
												vm.appSettings.loading = false;
												vm.errors.errorCode = error.name;
												vm.appSettings.pageTitle = 'error';
												vm.appSettings.leftPanelView = 'none';
												vm.appSettings.rightPanelView = 'errors';
												console.log(error.message);
												return;
											}
										},
										error: (error) => {
											vm.appSettings.loading = false;
											vm.errors.errorCode = error.name;
											vm.appSettings.pageTitle = 'error';
											vm.appSettings.leftPanelView = 'none';
											vm.appSettings.rightPanelView = 'errors';
											console.log(error.message);
											return;
										}
									});
								} catch (error) {
									vm.appSettings.loading = false;
									vm.errors.errorCode = error.name;
									vm.appSettings.pageTitle = 'error';
									vm.appSettings.leftPanelView = 'none';
									vm.appSettings.rightPanelView = 'errors';
									console.log(error.message);
									return;
								}
							},
							error: (error) => {
								vm.appSettings.loading = false;
								vm.errors.errorCode = error.name;
								vm.appSettings.pageTitle = 'error';
								vm.appSettings.leftPanelView = 'none';
								vm.appSettings.rightPanelView = 'errors';
								console.log(error.message);
								return;
							}
						});
					} catch (error) {
						vm.appSettings.loading = false;
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
					vm.appSettings.loading = false;
					vm.errors.errorCode = error.name;
					vm.appSettings.pageTitle = 'error';
					vm.appSettings.leftPanelView = 'none';
					vm.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			});
		},
		mobileSizeCheck: function() {
			console.log('mobileSizeCheck test');
			if ($(window).width() <= 500 ) {
				return true;
			} else {
				return false;
			}
		}
	}
});

Vue.component('right-customer-account', {
	props: ['appSettings', 'customer', 'errors', 'success'],
	methods: {
		getPrice: function(index) {
			var price = vm.customer.cart[index].price * vm.customer.cart[index].quantity;
			return price;
		},
		increaseQuantity: function(index) {
			vm.customer.cart[index].quantity++;
			//$('.cart-quantity-input').eq(index).val(vm.customer.cart[index].quantity);
			this.updatePrice(index);
		},
		decreaseQuantity: function(index) {
			vm.customer.cart[index].quantity--;
			//if quantity is going down to zero remove item otherwise update price
			if (vm.customer.cart[index].quantity <= 0) { 
				this.removeItem(index); 
			} else {
				this.updatePrice(index); 
			}
		},
		updatePrice: function(index) {
			//vm.customer.cart[index].albumName = '';
			//vm.customer.cart[index].calPrice = vm.customer.cart[index].price * vm.customer.cart[index].quantity;
			var calPrice = vm.customer.cart[index].price * vm.customer.cart[index].quantity;
			Vue.set(vm.customer.cart[index], 'calPrice', calPrice);
		},
		removeItem: function(index) {
			vm.customer.cart.splice(index, 1);
		},
		checkout: function() {
			resetFormErrors();
			if (vm.customer.loggedIn) {
				vm.appSettings.pageTitle = 'checkout';
				vm.appSettings.customerAccountPage = 'checkout';
				history.pushState({ appSettings: this.appSettings }, '');
			} else {
				this.appSettings.showRightLogo = false;
				this.appSettings.showLeftLogo = false;
				this.appSettings.showInfo = false;
				this.appSettings.showNav = false;

				this.appSettings.pageTitle = 'User Login';
				this.appSettings.leftPanelView = 'none';
				this.appSettings.rightPanelView = 'customerLogin';

				this.errorsExist = false;
				this.appSettings.loginForm = 'customerLogin';
				this.errors.formErrors.password = false;
				this.errors.formErrors.email = false;
				this.errors.formErrors.firstName = false;
				this.errors.formErrors.lastName = false;
				history.pushState({ appSettings: this.appSettings }, '');
			}
		},
		placeOrder: function() {
			resetFormErrors();
			var firstName = $('#checkout-first-name').val();
			var lastName = $('#checkout-last-name').val();
			var country = $('#checkout-country').val();
			var city = $('#checkout-city').val();
			var postalCode = $('#checkout-postal-code').val();
			var addressOne = $('#checkout-address-one').val();
			var addressTwo = $('#checkout-address-two').val();
			var saveAddress = $('#checkout-save-address').is(':checked');
			var cardType = $('#payment-method').val();
			var cart = JSON.stringify(vm.customer.cart);
			var totalPrice = this.totalPrice;

			//validation checks
			var firstNameValid = validate('name', firstName, null, 60, 1, true);
			var lastNameValid = validate('name', lastName, null, 60, 1, true);
			var countryValid = validate('text', country, null, 60, 3, true);
			var cityValid = validate('text', city, null, 60, 2, true);
			var postalCodeValid = validate('text', postalCode, null, 7, 6, true);
			var addressOneValid = validate('text', addressOne, null, 60, 8, true);
			var addressTwoValid = validate('text', addressTwo, null, 60, 2, false);

			if (firstNameValid !== true) {
				vm.customer.errors.billingForm.firstName = true;
				vm.customer.errors.billingForm.firstNameMessage = firstNameValid;
			}

			if (lastNameValid !== true) {
				vm.customer.errors.billingForm.lastName = true;
				vm.customer.errors.billingForm.lastNameMessage = lastNameValid;
			}

			if (countryValid !== true) {
				vm.customer.errors.billingForm.country = true;
				vm.customer.errors.billingForm.countryMessage = countryValid;
			}

			if (cityValid !== true) {
				vm.customer.errors.billingForm.city = true;
				vm.customer.errors.billingForm.cityMessage = cityValid;
			}

			if (postalCodeValid !== true) {
				vm.customer.errors.billingForm.postalCode = true;
				vm.customer.errors.billingForm.postalCodeMessage = postalCodeValid;
			}

			if (addressOneValid !== true) {
				vm.customer.errors.billingForm.addressOne = true;
				vm.customer.errors.billingForm.addressOneMessage = addressOneValid;
			}

			if (addressTwoValid !== true) {
				vm.customer.errors.billingForm.addressTwo = true;
				vm.customer.errors.billingForm.addressTwoMessage = addressTwoValid;
			}

			// if any errors exist, exit function
			for (var key in vm.customer.errors.billingForm) {
				if (vm.customer.errors.billingForm[key]) {
					console.log('key: ' + key + ' has errors');
					return;
				}
			}

			vm.appSettings.loading = true;
			$.ajax({
				url: 'data/index.php',
				type: 'POST',
				data: { 
					action: 'placeOrder',
					customerID: vm.customer.details.customerID,
					firstName: firstName,
					lastName: lastName,
					country: country,
					city: city,
					postalCode: postalCode,
					addressOne: addressOne,
					addressTwo: addressTwo,
					saveAddress: saveAddress,
					cardType: cardType,
					totalPrice: totalPrice,
					cart: cart
				},
				success: (result) => {
					try {
						console.log('place order test');
						console.log('result: ' + result);
						console.log('result error: ' + result.error);
						var result = JSON.parse(result);
						vm.appSettings.loading = false;
						if (!result.errors) {
							console.log('test');
							vm.customer.orders.push(result.order);
							if (result.address) {
								vm.customer.details.address = result.address;
								vm.customer.details.addressID = result.address.addressID;
							}
							vm.appSettings.customerAccountPage = 'orderPlaced';
							vm.appSettings.pageTitle = '';
						}
					} catch (error) {
						vm.appSettings.loading = false;
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
					vm.appSettings.loading = false;
					vm.errors.errorCode = error.name;
					vm.appSettings.pageTitle = 'error';
					vm.appSettings.leftPanelView = 'none';
					vm.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			});
		},
		editCustomer: function() {
			resetFormErrors();
			console.log('editCustomer test');
			var customerID = vm.customer.details.customerID;
			var firstName = $('#edit-first-name').val();
			var lastName = $('#edit-last-name').val();
			var email = $('#edit-email').val();
			var loginPassword = $('#edit-login-password').val();
			var newPassword = $('#edit-new-password').val();

			console.log('password1 length: ' + loginPassword.length);
			console.log('password2 length: ' + newPassword.length);
			var valid = newPassword.length ? true : false;
			console.log('valid: ' + valid);

			var country = $('#edit-country').val();
			var city = $('#edit-city').val();
			var postalCode = $('#edit-postal-code').val();
			var addressOne = $('#edit-address-one').val();
			var addressTwo = $('#edit-address-two').val();

			vm.success.success = false;
			vm.success.successMessage = '';

			if (!loginPassword.length) {
				console.log('password test');
				vm.errors.formErrors.password = true;
				vm.errors.formErrors.passwordMessage = 'You must enter the current password.';
				return;
			} else {
				vm.appSettings.loading = true;
				//check current password
				$.ajax({
					url: 'data/index.php',
					type: 'POST',
					data: { 
						action: 'checkValidLogin', 
						customerID: customerID,
						password: loginPassword
					},
					success: (result) => {
						try {
							console.log('result: ' + result);
							vm.appSettings.loading = false;
							if (!result) {
								vm.errors.formErrors.password = true;
								vm.errors.formErrors.passwordMessage = 'Incorrect Password.';
								return;
							} else {
								vm.errors.formErrors.password = false;
								vm.errors.formErrors.passwordMessage = '';
								editCustomerCallback();
							}
						} catch (error) {
							vm.appSettings.loading = false;
							vm.errors.errorCode = error.name;
							vm.appSettings.pageTitle = 'error';
							vm.appSettings.leftPanelView = 'none';
							vm.appSettings.rightPanelView = 'errors';
							console.log(error.message);
							return;
						}
					},
					error: (error) => {
						vm.appSettings.loading = false;
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				});
			}

			function editCustomerCallback() {
				console.log('editCustomer Callback test');
				//validation checks
				var firstNameValid = validate('name', firstName, null, 60, 1, true);
				var lastNameValid = validate('name', lastName, null, 60, 1, true);

				var emailValid = validate('email', email, null, 255, 3, true);
				var newPasswordValid = validate('text', newPassword, null, 60, 5, false);

				var countryValid, cityValid ,postalCodeValid, addressOneValid, addressTwoValid;

				//Check if any address fields have values
				var updateAddress = (country.length || city.length || postalCode.length || addressOne.length || addressTwo.length);
				console.log('updateAddress: ' + updateAddress);

				//If any address field has been entered then all address information is required
				if (updateAddress) {
					countryValid = validate('text', country, null, 60, 3, true);
					cityValid = validate('text', city, null, 60, 2, true);
					postalCodeValid = validate('text', postalCode, null, 7, 6, true);
					addressOneValid = validate('text', addressOne, null, 60, 8, true);
					addressTwoValid = validate('text', addressTwo, null, 60, 2, false);
				} else {
					countryValid = validate('text', country, null, 60, 3, false);
					cityValid = validate('text', city, null, 60, 2, false);
					postalCodeValid = validate('text', postalCode, null, 7, 6, false);
					addressOneValid = validate('text', addressOne, null, 60, 8, false);
					addressTwoValid = validate('text', addressTwo, null, 60, 2, false);
				}

				if (firstNameValid !== true) {
					vm.errors.formErrors.firstName = true;
					vm.errors.formErrors.firstNameMessage = firstNameValid;
				}

				if (lastNameValid !== true) {
					vm.errors.formErrors.lastName = true;
					vm.errors.formErrors.lastNameMessage = lastNameValid;
				}

				if (newPasswordValid !== true) {
					vm.errors.formErrors.passwordTwo = true;
					vm.errors.formErrors.passwordTwoMessage = newPasswordValid;
				}

				if (countryValid !== true) {
					vm.errors.formErrors.country = true;
					vm.errors.formErrors.countryMessage = countryValid;
				}

				if (cityValid !== true) {
					vm.errors.formErrors.city = true;
					vm.errors.formErrors.cityMessage = cityValid;
				}

				if (postalCodeValid !== true) {
					vm.errors.formErrors.postalCode = true;
					vm.errors.formErrors.postalCodeMessage = postalCodeValid;
				}

				if (addressOneValid !== true) {
					vm.errors.formErrors.addressOne = true;
					vm.errors.formErrors.addressOneMessage = addressOneValid;
				}

				if (addressTwoValid !== true) {
					vm.errors.formErrors.addressTwo = true;
					vm.errors.formErrors.addressTwoMessage = addressTwoValid;
				}

				// if any errors exist, exit function
				for (var key in vm.errors.formErrors) {
					if (vm.errors.formErrors[key]) {
						console.log('key: ' + key + ' has errors');
						return;
					}
				}

				vm.appSettings.loading = true;
				$.ajax({
					url: 'data/index.php',
					type: 'POST',
					data: { 
						action: 'editUser', 
						customerID: customerID,
						firstName: firstName,
						lastName: lastName,
						email: email,
						loginPassword: loginPassword,
						newPassword: newPassword,
						updateAddress: updateAddress,
						city: city,
						country: country,
						postalCode: postalCode,
						addressOne: addressOne,
						addressTwo: addressTwo
					},
					success: (result) => {
						try {
							console.log('result: ' + result);
							vm.appSettings.loading = false;
							if (!result.errors) {
								console.log('customer edit successful');
								result = JSON.parse(result);
								vm.customer.details = result.details;
								vm.success.success = true;
								vm.success.successMessage = 'User updated successfully.';
							}
						} catch (error) {
							vm.appSettings.loading = false;
							vm.errors.errorCode = error.name;
							vm.appSettings.pageTitle = 'error';
							vm.appSettings.leftPanelView = 'none';
							vm.appSettings.rightPanelView = 'errors';
							console.log(error.message);
							return;
						}
					},
					error: (error) => {
						vm.appSettings.loading = false;
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				});
			}

		},
		loadEditUser: function() {
			resetFormErrors();
			vm.appSettings.customerAccountPage = 'editCustomer';
			vm.appSettings.pageTitle = 'edit account';
			history.pushState({ appSettings: this.appSettings }, '');
		},
		mobileSizeCheck: function() {
			if ($(window).width() <= 500 ) {
				return true;
			} else {
				return false;
			}
		}
	},
	computed: {
		totalPrice: function() {
			console.log('test');
			var totalPrice = 0;
			if (typeof vm.customer.cart != 'undefined') {
				for (item in vm.customer.cart) {
					console.log('item: ' + item);
					totalPrice += parseInt(vm.customer.cart[item].calPrice);
				}
			}
			return totalPrice;
		}
	}
});

Vue.component('right-errors', {
	props: ['appSettings', 'errors'],
	methods: {
	}
});

Vue.component('right-search-list', {
	props: ['searchResults', 'pageTitle'],
	methods: {

	}
});

