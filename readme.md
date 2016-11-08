# Mailbox api

# Install
Run composer
`composer install`
Run Migrations files
`php artisan migrate`

## Importing the files
To import the json file used, you can run the following command after migrating the files .
`php artisan db:seed`

This will import the json file to table `messages` . 

## Endpoints
### POST '/api/register' ( register new user and create token )
- name: string
- email: string(email), unique

**Success**
```javascript
{
  "message": {
    "api_token": "API_TOKEN_HERE"
  }
}
```
**Fail**
```javascript
{
  "error": {
    "message": {
      "name": [
        "The name field is required."
      ],
      "email": [
        "The email field is required."
      ]
    },
    "status_code": 422
  }
}
```



### GET '/api/messages' ( list messages and filter them ( optional ) by status )
- api_token: string(hashed)
- status: archived\read ( optional )
  * if we passed this parameter then we can filter the messages by archived or read

**Success**
```javascript
{
  "total": 6,
  "per_page": 3,
  "current_page": 1,
  "last_page": 2,
  "next_page_url": "http://mail.app/api/messages?page=2",
  "prev_page_url": null,
  "from": 1,
  "to": 3,
  "data": [
    {
      "uid": 21,
      "sender": "Ernest Hemingway",
      "subject": "animals",
      "message": "This is a tale about nihilism. The story is about a combative nuclear engineer who hates animals. It starts in a ghost town on a world of forbidden magic. The story begins with a legal dispute and ends with a holiday celebration.",
      "read": 0,
      "archived": 1,
      "time_sent": "2016-03-29 08:24:27"
    },
    {
      "uid": 22,
      "sender": "Stephen King",
      "subject": "adoration",
      "message": "The story is about a fire fighter, a naive bowman, a greedy fisherman, and a clerk who is constantly opposed by a heroine. It takes place in a small city. The critical element of the story is an adoration.",
      "read": 0,
      "archived": 1,
      "time_sent": "2016-03-29 10:52:27"
    },
    {
      "uid": 23,
      "sender": "Virgina Woolf",
      "subject": "debt",
      "message": "The story is about an obedient midwife and a graceful scuba diver who is in debt to a fence. It takes place in a magical part of our universe. The story ends with a funeral.",
      "read": 0,
      "archived": 0,
      "time_sent": "2016-02-29 17:44:27"
    }
  ]
}
```
**Fail**
```javascript
{
  "error": {
    "message": "Unauthorized",
    "status_code": 401
  }
}
```

## Testing
`venor/bin/phpunit` to run test cases, tests can be found in folder `tests`