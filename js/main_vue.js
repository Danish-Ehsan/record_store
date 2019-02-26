var vm = new Vue({
	el: '#vueApp',
	data: {
		albums: [],
		artists: [],
		errorsExist: false,
		customer: {
			/*details: {
				address: {
					city: '',
					country: '',
					line1: '',
					line2: '',
					postalCode: ''
				}
			},*/
			cart: [],
			orders: [],
			loggedIn: false,
			incorrectLogin: false,
			errors: {
				forms: {
					firstName: false,
					firstNameMessage: '',
					lastName: false,
					lastNameMessage: '',
					country: false,
					countryMessage: '',
					city: false,
					cityMessage: '',
					postalCode: false,
					postalCodeMessage: '',
					addressOne: false,
					addressOneMessage: '',
					addressTwo: false,
					addressTwoMessage: ''
				},
			}
		},
		appSettings: {
			currentAlbum: 0,
			pageTitle: 'featured albums',
			showRightLogo: true,
			showLeftLogo: true,
			showInfo: false,
			showNav: false,
			leftPanelView: 'albumCovers',
			rightPanelView: 'albums',
			customerAccountPage: 'checkout',
			loginForm: 'customerLogin'
		},
		errors: {
			errorCode: '',
			errorMessage: '',
			formErrors: {
				email: false,
				emailMessage: '',
				password: false,
				passwordMessage: '',
				passwordTwo: false,
				passwordTwoMessage: '',
				firstName: false,
				firstNameMessage: '',
				lastName: false,
				lastNameMessage: '',
				country: false,
				countryMessage: '',
				city: false,
				cityMessage: '',
				postalCode: false,
				postalCodeMessage: '',
				addressOne: false,
				addressOneMessage: '',
				addressTwo: false,
				addressTwoMessage: ''
			}
		},
		success: {
			success: false,
			successMessage: ''
		}
	},
	methods: {
		toggleInfo: function() {
			this.appSettings.showInfo = !this.appSettings.showInfo;
		},
		toggleNav: function() {
			this.appSettings.showNav = !this.appSettings.showNav;
		},
		loadArtists: function() {
			$.ajax({
				url: 'data?action=getArtists',
				type: 'GET',
				success: (result) => {
					result = JSON.parse(result);
					this.artists = result;
					clearInterval(albumInfoInterval);

					this.appSettings.showNav = false;
					this.appSettings.leftPanelView = 'none';
					this.appSettings.rightPanelView = 'artistsList';
					this.appSettings.pageTitle = 'Artists';
					history.pushState({ appSettings: this.appSettings, artists: this.artists }, '');
				},
				error: (error) => {
					console.log(error);
					this.error = true;
				}
			});
		},
		loadAllAlbums: function() {
			$.ajax({
				url: 'data?action=getAlbumsCatalog',
				type: 'GET',
				success: (result) => {
					result = JSON.parse(result);
					this.albums = result;
					clearInterval(albumInfoInterval);

					// this.appSettings.showLeftLogo = false;
					this.appSettings.showNav = false;
					this.appSettings.leftPanelView = 'none';
					this.appSettings.rightPanelView = 'albumsList';
					this.appSettings.pageTitle = 'Albums';
					history.pushState({ appSettings: this.appSettings, albums: this.albums }, '');
				},
				error: (error) => {
					console.log(error);
					this.error = true;
				}
			});
		},
		loadHome: function() {
			$.ajax({
				url: 'data',
				type: 'GET',
				success: (result) => {
					console.log('loadhome test');
					var myObj = JSON.parse(result);
		    		vm.albums = myObj;
		    		this.appSettings.currentAlbum = 0;
					this.appSettings.showRightLogo = true;
					this.appSettings.showLeftLogo = true;
					this.appSettings.showInfo = false;
					this.appSettings.showNav = false;
					this.appSettings.leftPanelView = 'albumCovers';
					this.appSettings.rightPanelView = 'albums';
					this.appSettings.pageTitle = 'Featured Albums';
					albumInfoInterval =  setInterval(albumInfoScroll, 200);
					history.pushState({ appSettings: this.appSettings, albums: this.albums, artists: this.artists }, '');
					//need delay to allow logo to render
					setTimeout(function() {
						setLogoMargin();
					},1);
					
					scrollToTop();
				},
				error: (error) => {
					console.log(error);
					this.error = true;
				}
			});
		},
		loadCustomerLogin: function() {
			this.appSettings.showRightLogo = false;
			this.appSettings.showLeftLogo = false;
			this.appSettings.showInfo = false;
			this.appSettings.showNav = false;

			if (!this.customer.loggedIn) {
				this.appSettings.pageTitle = 'User Login';
				this.appSettings.leftPanelView = 'none';
				this.appSettings.rightPanelView = 'customerLogin';

				this.errorsExist = false;
				this.appSettings.loginForm = 'customerLogin';
				this.errors.formErrors.password = false;
				this.errors.formErrors.email = false;
				this.errors.formErrors.firstName = false;
				this.errors.formErrors.lastName = false;
			} else {
				this.appSettings.rightPanelView = 'customerAccount';
				this.appSettings.customerAccountPage = 'welcome';
				this.appSettings.leftPanelView = 'userMenu';
				this.appSettings.pageTitle = 'User Account';
			}
			history.pushState({ appSettings: this.appSettings }, '');
		},
		loadCustomerHome: function() {
			this.appSettings.showRightLogo = false;
			this.appSettings.showLeftLogo = false;
			this.appSettings.showInfo = false;
			this.appSettings.showNav = false;

			this.appSettings.rightPanelView = 'customerAccount';
			this.appSettings.leftPanelView = 'userMenu';
			vm.appSettings.customerAccountPage = 'welcome';
			vm.appSettings.pageTitle = 'User Account';
			history.pushState({ appSettings: this.appSettings }, '');
		},
		loadCustomerCart: function() {
			this.appSettings.showRightLogo = false;
			this.appSettings.showLeftLogo = false;
			this.appSettings.showInfo = false;
			this.appSettings.showNav = false;

			this.appSettings.rightPanelView = 'customerAccount';
			this.appSettings.leftPanelView = 'userMenu';
			vm.appSettings.customerAccountPage = 'cart';
			vm.appSettings.pageTitle = 'my cart';
			history.pushState({ appSettings: this.appSettings }, '');
		}
	},
	computed: {
		formattedPrice: function() {
			if (this.albums[this.appSettings.currentAlbum]) {
				var price = this.albums[this.appSettings.currentAlbum].price;
				//if price is full dollar amount don't show decimals
				if (price % 1 == 0) {
					price = Math.floor(price);
				}
				price = price.toString();
				price = '$' + price;
				return price;
			}
		},
		totalCartQuantity: function() {
			var total = 0;
			for (i = 0; i < this.customer.cart.length; i++) {
				total += this.customer.cart[i].quantity;
			}
			return total;
		}
	}
});

//console.log('appSettings.currentAlbum: ' + vm.albums[vm.appSettings.currentAlbum].artist);