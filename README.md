# Bozzooka

Lead catching app

## Getting Started


### Prerequisites

Please check out the following links if you are not familiar with Node.js or Grunt

 * [Node.js](https://nodejs.org/en/)
 * [Grunt](http://gruntjs.com/)


### Installing

NPM

In the command line:

```
git clone https://<Your user name>@bitbucket.org/herolo/buzzooka.git
```

```
npm install
```

## Running Grunt before deploying to AWS

### Gruntfile.js config

 under concat key include your vendor, app and css files  

### Running Grunt

In command line type:

```
grunt prod
```
### Result

In the app directory you will see a "dist" folder which is the newly created build version, a zip file "version.zip" which is "dist" folder zipped.

## Deployment

Use the zip file to deploy the app build version.