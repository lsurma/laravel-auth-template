# Laravel extended user authentication
Laravel application with modified and extended user authentication processes.
Currently in development and should not be used in any production services.
Goal of this project is to make safe, robust and configurable package for user authentication processes.

## TODO
- [x] Captcha in registration 
- [x] Password strength validation rule
- [x] E-mail domain validation 
- [x] Prevent login when user has not verified it's e-mail address
- [x] E-mail verification notification
- [x] Information about e-mail verification after register
- [x] Session authentication (with custom token, instead of hashed user password like laravels do)
- [ ] Config based on laravel guards, instead of custom groups, to maintain compatibility with some core functionalities, events etc.
- [ ] User session tracking
- [ ] Forcing reset password 
- [ ] 2FA 
- [ ] Socail login (via socialite)
- [ ] Password status (in case of login via social provider)
- [x] Make Blacklist elements (migrations, models etc.) as separated package 
- [ ] IP metadata gathering on login/register via external API (for eg. https://ipstack.com/)
- [ ] Validation based on IP geolocation and some other metadata 
- [ ] Make everyting mentioned above as configurable as possible
- [ ] Convert to package

## License
[MIT license](https://opensource.org/licenses/MIT).
