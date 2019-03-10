var vm = new Vue({
	el: '#vueApp',
	data: {
		albums: [],
		artists: [],
		errorsExist: false,
		searchResults: {
			albums: [],
			artists: [],
			songs: []
		},
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
			loading: false,
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
			this.appSettings.loading = true;
			this.appSettings.showNav = false;
			$.ajax({
				url: 'data?action=getArtists',
				type: 'GET',
				success: (result) => {
					try {
						result = JSON.parse(result);
						this.artists = result;
						clearInterval(albumInfoInterval);
						vm.appSettings.loading = false;

						this.appSettings.leftPanelView = 'none';
						this.appSettings.rightPanelView = 'artistsList';
						this.appSettings.pageTitle = 'Artists';
						history.pushState({ appSettings: this.appSettings, artists: this.artists }, '');
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
					this.appSettings.loading = false;
					vm.errors.errorCode = error.name;
					vm.appSettings.pageTitle = 'error';
					vm.appSettings.leftPanelView = 'none';
					vm.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			});
		},
		loadAllAlbums: function() {
			this.appSettings.loading = true;
			this.appSettings.showNav = false;
			$.ajax({
				url: 'data?action=getAlbumsCatalog',
				type: 'GET',
				success: (result) => {
					try {
						result = JSON.parse(result);
						this.albums = result;
						clearInterval(albumInfoInterval);

						// this.appSettings.showLeftLogo = false;
						this.appSettings.loading = false;
						this.appSettings.leftPanelView = 'none';
						this.appSettings.rightPanelView = 'albumsList';
						this.appSettings.pageTitle = 'Albums';
						history.pushState({ appSettings: this.appSettings, albums: this.albums }, '');
					} catch (error) {
						this.appSettings.loading = false;
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
					this.appSettings.loading = false;
					vm.errors.errorCode = error.name;
					vm.appSettings.pageTitle = 'error';
					vm.appSettings.leftPanelView = 'none';
					vm.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			});
		},
		loadHome: function() {
			this.appSettings.loading = true;
			this.appSettings.showNav = false;
			$.ajax({
				url: 'data?action=getFeatured',
				type: 'GET',
				success: (result) => {
					try {
						console.log('loadhome test');
						var result = JSON.parse(result);
			    		vm.albums = result;
			    		this.appSettings.loading = false;
			    		this.appSettings.currentAlbum = 0;
						this.appSettings.showRightLogo = true;
						this.appSettings.showLeftLogo = true;
						this.appSettings.showInfo = false;
						this.appSettings.leftPanelView = 'albumCovers';
						this.appSettings.rightPanelView = 'albums';
						this.appSettings.pageTitle = 'featured albums';
						albumInfoInterval =  setInterval(albumInfoScroll, 200);
						history.pushState({ appSettings: this.appSettings, albums: this.albums, artists: this.artists }, '');
						//need delay to allow logo to render
						setTimeout(function() {
							setLogoMargin();
						},1);
						
						scrollToTop();
					} catch (error) {
						this.appSettings.loading = false;
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
					this.appSettings.loading = false;
					vm.errors.errorCode = error.name;
					vm.appSettings.pageTitle = 'error';
					vm.appSettings.leftPanelView = 'none';
					vm.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			});
		},
		loadAlbum: function(albumID) {
			console.log('albumID: ' + albumID);
			this.appSettings.loading = true;
			this.appSettings.showNav = false;
			$.ajax({
				url: 'data?action=getAlbum&albumID=' + albumID,
				type: 'GET',
				success: (result) => {
					try {
						console.log(result);
						result = JSON.parse(result);
						vm.albums = [result];

						this.appSettings.loading = false;
						this.appSettings.showRightLogo = false;
						this.appSettings.showLeftLogo = false;
						this.appSettings.showInfo = true;
						this.appSettings.leftPanelView = 'albumCovers';
						this.appSettings.rightPanelView = 'albums';
						//vm.appSettings.showAlbums = true;
						//vm.appSettings.showArtistsList = false;
						//vm.appSettings.showAlbumsList = false;
						this.appSettings.pageTitle = '';
						//global interval variable
						albumInfoInterval =  setInterval(albumInfoScroll, 200);
						history.pushState({ appSettings: vm.appSettings, albums: vm.albums }, '');
					} catch (error) {
						this.appSettings.loading = false;
						this.errors.errorCode = error.name;
						this.appSettings.pageTitle = 'error';
						this.appSettings.leftPanelView = 'none';
						this.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
					this.appSettings.loading = false;
					this.errors.errorCode = error.name;
					this.appSettings.pageTitle = 'error';
					this.appSettings.leftPanelView = 'none';
					this.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			});
		},
		loadArtistCatalog: function(artistID) {
			console.log(artistID);
			this.appSettings.loading = true;
			this.appSettings.showNav = false;
			$.ajax({
				url: 'data?action=getArtistCatalog&artistID=' + artistID,
				type: 'GET',
				success: (result) => {
					try {
						result = JSON.parse(result);
						this.albums = result;

						this.appSettings.loading = false;
						this.appSettings.showRightLogo = false;
						this.appSettings.showLeftLogo = false; //causing errors with album info scroll functions
						this.appSettings.showInfo = false;
						this.appSettings.leftPanelView = 'albumCovers';
						this.appSettings.rightPanelView = 'albums';
						//vm.appSettings.showAlbums = true;
						//vm.appSettings.showArtistsList = false;
						this.appSettings.pageTitle = result[0].artistName;
						//global interval variable
						albumInfoInterval =  setInterval(albumInfoScroll, 200);
						history.pushState({ appSettings: vm.appSettings, albums: vm.albums }, '');
					} catch (error) {
						this.appSettings.loading = true;
						this.errors.errorCode = error.name;
						this.appSettings.pageTitle = 'error';
						this.appSettings.leftPanelView = 'none';
						this.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
					this.appSettings.loading = true;
					this.errors.errorCode = error.name;
					this.appSettings.pageTitle = 'error';
					this.appSettings.leftPanelView = 'none';
					this.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			});
		},
		loadCustomerLogin: function() {
			resetFormErrors();
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
		},
		loadRandomAlbum: function() {
			this.appSettings.loading = true;
			this.appSettings.showNav = false;
			$.ajax({
				url: 'data?action=getRandom',
				type: 'GET',
				success: (result) => {
					try {
						var result = JSON.parse(result);
			    		vm.albums = [result];

			    		this.appSettings.loading = false;
			    		this.appSettings.currentAlbum = 0;
						this.appSettings.showRightLogo = false;
						this.appSettings.showLeftLogo = false;
						this.appSettings.showInfo = false;
						this.appSettings.leftPanelView = 'albumCovers';
						this.appSettings.rightPanelView = 'albums';
						this.appSettings.pageTitle = 'random album';
						history.pushState({ appSettings: this.appSettings, albums: this.albums, artists: this.artists }, '');
					} catch (error) {
						this.appSettings.loading = false;
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
					this.appSettings.loading = false;
					vm.errors.errorCode = error.name;
					vm.appSettings.pageTitle = 'error';
					vm.appSettings.leftPanelView = 'none';
					vm.appSettings.rightPanelView = 'errors';
					console.log(error.message);
					return;
				}
			});
		},
		submitSearch: function() {
			var searchVal = $('#nav-search').val();

			//if search input is empty load search page and exit
			if (!searchVal.length) {
				this.searchResults = { albums: [], artists: [], songs: [] };
				this.appSettings.showNav = false;
				this.appSettings.leftPanelView = 'none';
				this.appSettings.rightPanelView = 'searchList';
				this.appSettings.pageTitle = 'Search result';
				history.pushState({ appSettings: this.appSettings, albums: this.albums }, '');
				return;
			}

			this.appSettings.loading = true;
			this.appSettings.showNav = false;
			$.ajax({
				url: 'data/index.php',
				type: 'POST',
				data: {
					action: 'search',
					searchVal: searchVal
				},
				success: (result) => {
					try {
						console.log(result);
						var result = JSON.parse(result);

						this.searchResults = result;
						clearInterval(albumInfoInterval);

						// this.appSettings.showLeftLogo = false;
						this.appSettings.loading = false;
						this.appSettings.leftPanelView = 'none';
						this.appSettings.rightPanelView = 'searchList';
						this.appSettings.pageTitle = 'Search result';
						history.pushState({ appSettings: this.appSettings, albums: this.albums }, '');
					} catch (error) {
						this.appSettings.loading = false;
						vm.errors.errorCode = error.name;
						vm.appSettings.pageTitle = 'error';
						vm.appSettings.leftPanelView = 'none';
						vm.appSettings.rightPanelView = 'errors';
						console.log(error.message);
						return;
					}
				},
				error: (error) => {
					this.appSettings.loading = false;
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
			if ($(window).width() <= 500 ) {
				return true;
			} else {
				return false;
			}
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