# OwnTracks

## Introduction

The [OwnTracks Drupal module](https://www.drupal.org/project/owntracks) provides
an HTTP endpoint for the [OwnTracks smartphone app](http://www.owntracks.org).

The OwnTracks smartphone app periodically collects location data from
smartphones and sends this data to an HTTP or MQTT endpoint.

The OwnTracks Drupal module leverages the following technology:

* the Drupal entity API to store and display the data

* the Drupal Views module to list the data

* the [Leaflet](http://leafletjs.com) JavaScript library and a configurable map
tile provider such as [OpenStreetMap](https://www.openstreetmap.org) to
visualize the data

There are some [Screenshots](#screenshots) at the end of this document.

## Installation

### Drupal Module

Download the module from the [project page](https://www.drupal.org/project/owntracks)
or fetch it with Drush or Composer:

* `drush dl owntracks`

* `composer require drupal/owntracks:^1.0`

There are no external dependencies. Go to `admin/modules` and enable it or use
Drush:

* `drush en owntracks`

### Smartphone App

The app can be downloaded and installed from the Android and iOS app stores:

* [OwnTracks for Android](https://play.google.com/store/apps/details?id=org.owntracks.android)

* [OwnTracks for iOS](https://itunes.apple.com/us/app/mqttitude/id692424691)

## Configuration

### Drupal Module

Go to `admin/config/owntracks/map` to configure the map settings. You need to
provide three settings, for example:

* Tile layer URL

   `http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`

* Tile layer attribution

   `Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors`

* Polyline color (hex or written-out)

   `blue`

More info: <https://switch2osm.org/using-tiles/getting-started-with-leaflet/>

Go to `admin/people/permissions` to configure the OwnTracks permissions. With
the `create owntracks entites`, users can post location data to the endpoint and
with the `view own owntracks entities`, they can view their location data (maps
and listings).

### Smartphone App

#### Android

Configure the following settings:

* Preferences > Connection > Mode

   `Private HTTP`

* Preferences > Connection > Host

   `[url of your drupal site]/owntracks/endpoint`

* Preferences > Connection > Identification

   `username and password of your Drupal account`

#### iOs

* TBD

### Testing

#### Android

In the top right corner, tap the `Upload` button (second from the right), tap
the `Menu` button in the top left corner and then in the menu tap `Status`. The
endpoint state message should be `Response 200, 1`.

#### iOS

* TBD

#### Drupal

Go to `admin/content`. You should see three tabs: `OwnTracks Location`,
`OwnTracks Transition` and `OwnTracks Waypoint`. If you don't see them right
after enabling the module, clear your caches. Click `OwnTracks Location` and you
should see the location that you just uploaded from your smartphone app. In the
Operations column, click `View` and you should see a map with a location marker.

## Features

Currently, the OwnTracks Drupal module supports three different OwnTracks
payload types:

* Locations

* Waypoints

* Transitions

### Locations

Locations are the heart of the OwnTracks app. Location records are created
based on motion and time, that is, the smartphone app automatically posts a
location record if either a configured amount of time has passed or a configured
distance was covered since the previous record.

The respective intervals can be configured in the smartphone app and it is
recommended to try out different settings to get the desired location record
frequency.

The OwnTracks Drupal module stores the records and displays them as:

* Single location record with a map and additonal data such as accuracy

   `owntracks_location/[entity_id]`

* Track map per user

   `user/[uid]/owntracks`

* Most recently recorded (current) location per user

   `user/[uid]/owntracks/current`

* Tabular location listing

   `user/[uid]/owntracks/location`

### Waypoints

A waypoint is a location with a radius (aka geo-fence). Waypoints must be created
in the smartphone app and uploaded manually to the endpoint. Once a user enters
or leaves a waypoint's radius, the smartphone app emits a transition event (see
below).

#### Android

In order to create waypoints in the Android smartphone app, tap the `Menu` icon
in the top left corner. In the menu tap `Regions` and then in the top right
corner tap the `Plus` icon (second from the right). Once the regions are added,
they have to be uploaded to the endpoint server. Tap the `Options` icon in the
top right corner and then tap `Publish Waypoints`.

#### iOS

* TBD

#### Testing

Go to `user/[uid]/owntracks/waypoint` to
make sure that the waypoints were uploaded.

### Transitions

When a user enters or leaves the radius of a waypoint, the smartphone app
automatically emits a transition event to the endpoint server. This event is
recorded by the OwnTracks module along with additional data such as:

* type of transition event (enter or leave)

* timestamp when the transition occurred

* the geolocation of the transition

* accuracy of the geolocation

* reference to the transition's waypoint

* tracker that recorded the event

Go to `user/[uid]/owntracks/transition` to see a listing of transitions.

### Friends

This feature will be added in a future version of the module and allows for
sharing waypoints and following the location of other users.

## Screenshots

### Track Map

![Track Map](https://owntracks.dunix-data.de/sites/default/files/2018-01/Track.jpg)

### Current Location Map

![Current Location Map](https://owntracks.dunix-data.de/sites/default/files/2018-01/Current.jpg)

### Location Listing

![Location Listing](https://owntracks.dunix-data.de/sites/default/files/2018-01/Locations.jpg)

### Waypoint Listing

![Waypoint Listing](https://owntracks.dunix-data.de/sites/default/files/2018-01/Waypoints.jpg)

### Transition Listing

![Transition Listing](https://owntracks.dunix-data.de/sites/default/files/2018-01/Transitions.jpg)
