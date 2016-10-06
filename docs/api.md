## TTC - Survey platform API

To make an API call you need a valid token. The tokens are restricted by IP address and must be sent via HTTP GET. All the responses are UTF-8 JSON encoded.

### Requesting profile based on phone number

#### Request

HTTP GET: `/api/v1/profile/<phonenumber>?token=<token>`

#### Response


| Field		| Type		| Notes		|
|-----------|-----------|-----------|
| `identifier`		| string		| 8 characters a-zA-Z0-9			|
| `url`				| string		| Shortened url to the profile		|
| `phonenumber`		| string		| 									|
| `name`			| string		| Full name of the person			|
| `gender`			| string		| Options: "male", "female", "" 	|
| `birthday`		| string		| ISO 8601, yyyy-mm-dd				|
| `geo_city`		| string		| 									|
| `geo_lat`			| string		| e.g. "-79.981776"					|
| `geo_lng`			| string		| e.g. "51.93168" 					|
| `geo_country`		| string		| ISO 3166-1, 2 chars				|
| `language`		| string		| ISO 639-1, 2 chars				|
| `owner`			| string		| 				 					|
| `batch`			| string		| 				 					|
| `device`			| string		| Options: "smart", "feature"       |
| `status`			| string		| Can be "found", "not found" or "error". When "not found", the above fields will not be included in the response. In case of "error" the `error` field wil contain the error message.				|
| `error`			| string		| Optional. Contains the error message.						|
| `response_id`		| int			| unique identifier for each api call						|


### Create or update a profile

#### Request

HTTP POST: `/api/v1/profile?token=<token>`

###### Parameters

The only required parameters are `phonenumber`, `language` and `geo_country`, all others are optional and only restricted by the expected formats (see notes in the table above). If a profile with the provided `phonenumber` is found, the profile will be updated. If it's not found, a new profile will be created. In both cases the provided POST parameters will be used to update/create.



The parameters `identifier`, `status` and `response_id` cannot be used.


#### Response

| Field		| Type		| Notes		|
|-----------|-----------|-----------|
| `identifier`		| string			| 8 characters a-zA-Z0-9 								|
| `url`				| string			| Shortened url to the profile							|
| `status`			| string			| Can be "updated", "created" or "error".				|
| `error`			| string			| Optional. Contains the error message.					|
| `response_id`		| int				| unique identifier for each api call					|


<br />
<br />


### Examples

##### Getting profile

HTTP GET: `http://backend.mphone.link/api/v1/profile/1268905082?token=123`

`{"identifier":"ywfU8zEw","url":"http:\/\/localhost:8000\/profile\/ywfU8zEw","phonenumber":"1268905082","name":"Tania Terry","gender":"female","birthday":"1982-05-02","geo_city":"Birlay Littelton","geo_lat":"-79.981776","geo_lng":"51.93168","owner":"","batch":"","geo_country":"ko","language":"en","status":"found","response_id":44}`


##### Updating profile

HTTP POST: `http://backend.mphone.link/api/v1/profile?token=123`

Params: `phonenumber=1184255128, language=en, geo_country=se`

`{"identifier":"7sd1NpIl","url":"http:\/\/localhost:8000\/profile\/7sd1NpIl","status":"updated","response_id":43}`





