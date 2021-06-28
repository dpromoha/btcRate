# btcRate

## General info

In the 21st century, more and more importance is being attached to cryptocurrencies. The rising star is, of course, Bitcoin.

With the help of this web api, you can find out the current bitcoin rate in hryvnia.


## Installation

To use my web api you have to install and configure http server (for example, Apache). Also, you have to install PHP. 

For better experience, you can use Postman.


## How To Use

- **Clone this repository and go into the repository:**

$ git clone https://github.com/dpromoha/btcRate.git && cd btcRate

- **Open postman:**

write a first request

## Usage:
    
    - localhost/user/create (POST)

At first, you need to register with your email address and password. If you successfully sign up, you will get your access token. It helps you to get the desired bitcoin value converted to hryvnia.

![signUpFinal](https://user-images.githubusercontent.com/46355522/123677942-6183ac00-d84e-11eb-8293-924d25fc7b8e.gif)


	- localhost/user/login (POST)

If you forget your access token, you always can to login and get it. So, in this request you have possibility to login with your email and password. 

![signInFinal](https://user-images.githubusercontent.com/46355522/123678011-72ccb880-d84e-11eb-9f4b-a2787e5c0760.gif)


	- localhost/btcRate (GET)

Eventually, in this request you get what you want all this time. So, write your access token and you will see how many hryvnias you have to spend to buy 1 bitcoin.

![btcRateFinal](https://user-images.githubusercontent.com/46355522/123678083-8710b580-d84e-11eb-9513-95c2733cec6d.gif)
