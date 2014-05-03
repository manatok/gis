PHP map tile engine
===
This map tile engine was created using PHP and MySQL (with spatial data types) for use with the Google Maps API. Spatial data is loaded in via [.shp](http://en.wikipedia.org/wiki/Shapefile) and [.dbf](http://en.wikipedia.org/wiki/DBase) files.

This project is split into 2 sections, the tile engine and the example which uses the tile engine. The example is VERY simplistic and does not provide a proper interface for managing the layers. At the moment it illustrates how to import a .shp file and how this data can be viewed in Google Maps.

## Demo ##
Here is a demo of the map tile engine in action. I have only drawn the world boundaries.
[Map Example](http://manatok.com/gis/src/example/index.php?action=map)

## Supported Spatial Types ##
1. Polygon
2. Linestring
3. Point

## Installation ##
1. Clone the repo onto your server
2. Import the /db/ddl.sql file into MySQL
3. Change the MySQL connection details in the config file src/Gis/Config/default.php
4. To get the example running you will also need to edit the 'basePath' var in the src/example/html/map

### Running the example ###
1. Navigate to the src/example/index.php file
2. You should see world_simple.dbf and world_simple.shp file displaying in a list (This is an example data set)
3. If you click the preview link next to the .shp file you can inspect the first 10 records in the .shp file
4. If you click the load link you will be taken to a form where you can name the layer, select the set name (which comes from the .dbf file) and then import the dataset.
5. If the data imported successfully (you can have a look in the layers, sets and polygons table to confirm) you should be able to click on the 'Map Example' link at the top.

### Configuring ###
Since there is no interface for managing layers properly there are some things you need to know how to do manually until such time as an interface is developed. The fields on the layers table are important for how and when a layer is shown on the map. Here are some of the fields and what they do:

| Field | Purpose |
--- | ---
color		| the color this layer will display as, saved as a hex value
enabled 	| 1 = enabled , 0 = disabled
position	| the order in which the layers are drawn. 0 = first ... n = last
zoom_min	| the zoom level at which this layer will first appear
zoom_max 	| the last zoom level before this layer stops showing. Useful for displaying hi-res data only when you have zoomed in


## Architecture ##
more to come here...

### Database ###
The spatial tables (polygon, point and linestring) had to use the MyISAM storage engine in order to make use of MySQL's spatial indexes.

## Road Map ##
1. Replace the example with a proper admin section for managing the layers.
2. Cache the map tiles.
3. Support KML files.
4. Support Multilines
 
