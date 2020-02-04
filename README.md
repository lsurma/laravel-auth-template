# Laravel extended user authentication
Laravel application with modified and extended user authentication processes.
Currently in development and should not be used in any production services.
Goal of this project is to make safe, robust and configurable package for user authentication processes.

## TODO
- [x] Captcha in registration 
- [x] Password strength validation rule
- [x] E-mail domain validation 
- [x] Prevent login when user has not verified it's e-mail address (configurable)
- [ ] E-mail verification notification (configurable)
- [ ] User session tracking
- [ ] Session authentication (with custom token, instead of hashed user password like laravels do)
- [ ] 2FA 
- [ ] Forcing reset password 
- [ ] Socail login (via socialite)
- [ ] Password status (in case of login via social provider)
- [ ] Make everyting mentioned above as configurable as possible
- [ ] Make Blacklist elements (migrations, models etc.) as separated package 
- [ ] IP metadata gathering on login/register via external API (for eg. https://ipstack.com/)
- [ ] Validation based on IP geolocation and some other metadata 

## License
[MIT license](https://opensource.org/licenses/MIT).
