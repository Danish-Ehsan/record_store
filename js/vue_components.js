
Vue.component('left-album-covers', {
	props: ['albums', 'appSettings'],
	methods: {
		toggleInfo: function() {
			vm.appSettings.showInfo = !vm.appSettings.showInfo;
		}
	}
});

Vue.component('left-user-menu', {
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
			vm.appSettings.customerAccountPage = 'editCustomer';
			vm.appSettings.pageTitle = 'edit account';
			vm.success.success = false;
			history.pushState({ appSettings: this.appSettings }, '');
		},
		logout: function() {
			vm.customer.details = {};
			vm.customer.loggedIn = false;
			vm.appSettings.showRightLogo = false;
			vm.appSettings.showLeftLogo = false;
			vm.appSettings.showInfo = false;
			vm.appSettings.showNav = false;
			vm.appSettings.pageTitle = 'User Login';
			vm.appSettings.leftPanelView = 'none';
			vm.appSettings.rightPanelView = 'customerLogin';
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
		loadArtistCatalog: function(artistID) {
			console.log(artistID);
			$.ajax({
				url: 'data?action=getArtistCatalog&artistID=' + artistID,
				type: 'GET',
				success: (result) => {
					try {
						result = JSON.parse(result);
						console.log(result);
						vm.albums = result;

						vm.appSettings.showRightLogo = false;
						vm.appSettings.showLeftLogo = false; //causing errors with album info scroll functions
						vm.appSettings.showInfo = false;
						vm.appSettings.showNav = false;
						vm.appSettings.leftPanelView = 'albumCovers';
						vm.appSettings.rightPanelView = 'albums';
						//vm.appSettings.showAlbums = true;
						//vm.appSettings.showArtistsList = false;
						vm.appSettings.pageTitle = result[0].artistName;
						//global interval variable
						albumInfoInterval =  setInterval(albumInfoScroll, 200);
						history.pushState({ appSettings: vm.appSettings, albums: vm.albums }, '');
					} catch (error) {
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
					vm.errors.errorCode = error.name;
					vm.appSettings.pageTitle = 'error';
					vm.appSettings.leftPanelView = 'none';
					vm.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			});
		}
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
		loadAlbum: function(albumID) {
			$.ajax({
				url: 'data?action=getAlbum&albumID=' + albumID,
				type: 'GET',
				success: (result) => {
					try {
						console.log(result);
						result = JSON.parse(result);
						vm.albums = [result];

						vm.appSettings.showRightLogo = false;
						vm.appSettings.showLeftLogo = false;
						vm.appSettings.showInfo = true;
						vm.appSettings.showNav = false;
						vm.appSettings.leftPanelView = 'albumCovers';
						vm.appSettings.rightPanelView = 'albums';
						//vm.appSettings.showAlbums = true;
						//vm.appSettings.showArtistsList = false;
						//vm.appSettings.showAlbumsList = false;
						vm.appSettings.pageTitle = '';
						//global interval variable
						albumInfoInterval =  setInterval(albumInfoScroll, 200);
						history.pushState({ appSettings: vm.appSettings, albums: vm.albums }, '');
					} catch (error) {
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
					vm.errors.errorCode = error.name;
					vm.appSettings.pageTitle = 'error';
					vm.appSettings.leftPanelView = 'none';
					vm.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			});
		}
	}
});

Vue.component('right-customer-login', {
	props: ['appSettings', 'customer', 'errors'],
	methods: {
		submitLogin: function() {
			var email = $('#login-email').val();
			var password = $('#login-password').val();
			console.log('email: ' + email);
			console.log('password: ' + password);
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
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
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
			vm.appSettings.loginForm = 'createCustomer';
			vm.appSettings.pageTitle = 'Register User';
			history.pushState({ appSettings: vm.appSettings }, '');
		},
		createCustomer: function() {
			var firstName = $('#first-name').val();
			var lastName = $('#last-name').val();
			var email = $('#create-login-email').val();
			var password = $('#create-login-password').val();
			var confirmPassword = $('#create-confirm-password').val();

			//reset all errors
			vm.errors.formErrors.password = false;
			vm.errors.formErrors.passwordMessage = '';
			vm.errors.formErrors.passwordTwo = false;
			vm.errors.formErrors.passwordTwoMessage = '';
			vm.errors.formErrors.email = false;
			vm.errors.formErrors.emailMessage = '';
			vm.errors.formErrors.firstName = false;
			vm.errors.formErrors.firstNameMessage = '';
			vm.errors.formErrors.lastName = false;
			vm.errors.formErrors.lastNameMessage = '';

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
												vm.errors.errorCode = error.name;
												vm.appSettings.pageTitle = 'error';
												vm.appSettings.leftPanelView = 'none';
												vm.appSettings.rightPanelView = 'errors';
												console.log(error.message);
												return;
											}
										},
										error: (error) => {
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
							},
							error: (error) => {
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
				},
				error: (error) => {
					vm.errors.errorCode = error.name;
					vm.appSettings.pageTitle = 'error';
					vm.appSettings.leftPanelView = 'none';
					vm.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			});
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
			vm.appSettings.pageTitle = 'checkout';
			vm.appSettings.customerAccountPage = 'checkout';
			history.pushState({ appSettings: this.appSettings }, '');
		},
		placeOrder: function() {
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

			//reset all errors
			vm.customer.errors.forms.firstName = false;
			vm.customer.errors.forms.firstNameMessage = '';
			vm.customer.errors.forms.lastName = false;
			vm.customer.errors.forms.lastNameMessage = '';
			vm.customer.errors.forms.country = false;
			vm.customer.errors.forms.countryMessage = '';
			vm.customer.errors.forms.city = false;
			vm.customer.errors.forms.cityMessage = '';
			vm.customer.errors.forms.postalCode = false;
			vm.customer.errors.forms.postalCodeMessage = '';
			vm.customer.errors.forms.addressOne = false;
			vm.customer.errors.forms.addressOneMessage = '';
			vm.customer.errors.forms.addressTwo = false;
			vm.customer.errors.forms.addressTwoMessage = '';

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
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
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

			vm.errors.formErrors.firstName = false;
			vm.errors.formErrors.firstNameMessage = '';
			vm.errors.formErrors.lastName = false;
			vm.errors.formErrors.lastNameMessage = '';
			vm.errors.formErrors.emailMessage = '';
			vm.errors.formErrors.password = false;
			vm.errors.formErrors.passwordMessage = '';
			vm.errors.formErrors.passwordTwo = false;
			vm.errors.formErrors.passwordTwoMessage = '';			
			vm.errors.formErrors.country = false;
			vm.errors.formErrors.countryMessage = '';
			vm.errors.formErrors.city = false;
			vm.errors.formErrors.cityMessage = '';
			vm.errors.formErrors.postalCode = false;
			vm.errors.formErrors.postalCodeMessage = '';
			vm.errors.formErrors.addressOne = false;
			vm.errors.formErrors.addressOneMessage = '';
			vm.errors.formErrors.addressTwo = false;
			vm.errors.formErrors.addressTwoMessage = '';

			vm.success.success = false;
			vm.success.successMessage = '';

			if (!loginPassword.length) {
				console.log('password test');
				vm.errors.formErrors.password = true;
				vm.errors.formErrors.passwordMessage = 'You must enter the current password.';
				return;
			} else {
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
							vm.errors.errorCode = error.name;
							vm.appSettings.pageTitle = 'error';
							vm.appSettings.leftPanelView = 'none';
							vm.appSettings.rightPanelView = 'errors';
							console.log(error.message);
							return;
						}
					},
					error: (error) => {
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
							if (!result.errors) {
								console.log('customer edit successful');
								result = JSON.parse(result);
								vm.customer.details = result.details;
								vm.success.success = true;
								vm.success.successMessage = 'User updated successfully.';
							}
						} catch (error) {
							vm.errors.errorCode = error.name;
							vm.appSettings.pageTitle = 'error';
							vm.appSettings.leftPanelView = 'none';
							vm.appSettings.rightPanelView = 'errors';
							console.log(error.message);
							return;
						}
					},
					error: (error) => {
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
			vm.appSettings.customerAccountPage = 'editCustomer';
			vm.appSettings.pageTitle = 'edit account';
			history.pushState({ appSettings: this.appSettings }, '');
		},
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