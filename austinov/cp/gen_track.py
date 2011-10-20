#!/usr/bin/python

import argparse
import math
import random
import simplejson as json
import sys
import time
import urllib

def parse_args():
  parser = argparse.ArgumentParser(
  description='Script generates track by basepoints passed as file from \
               Cloudmade in json format. Otherwise it can use Google routes \
               to get some basepoints (less precise)')
  parser.add_argument('-f', dest='filename', action='store', type=str,
                      help='File in json format from Cloudmade')
  parser.add_argument('-o', dest='out', action='store', type=str,
                      help='Output file name')
  parser.add_argument('-t', dest='time', action='store', type=str,
                      help='Starttime timestamp for track to generate')
  parser.add_argument('-l', dest='lsp', action='store', type=int,
                      help='Lower limit for speed generator random')
  parser.add_argument('-u', dest='usp', action='store', type=int,
                      help='Upper limit for random speed generation')
  parser.add_argument('-m', dest='gen_map',action='store_true',default=False,
                      help='Generate map showing the track.')
  parser.add_argument('-V', dest='verbose',action='store_true',default=False,
                      help='Show verbose output.')
  return parser.parse_args()

def get_basepoints_from_google_route(o, d, wpts, s, **geo_args):
  GEOCODE_BASE_URL = 'http://maps.googleapis.com/maps/api/directions/json'
  ORIG = "50.18497,30.28158"
  DEST = "50.41474,30.51961"
  WPTS = "50.1833907,30.3059559|50.180763,30.3117672|50.393294,30.488439"
  geo_args.update({
    'origin': o,
    'destination': d,
    'waypoints': wpts,
    'sensor': s
  })
  verbose = False
  url = GEOCODE_BASE_URL + '?' + urllib.urlencode(geo_args)
  result = json.load(urllib.urlopen(url))
  pointslist = []
  for route in result['routes']:
    if verbose: print 'Found route:', route['summary']
    stepcnt = 0
    for leg in route['legs']:
      if verbose: print 'Leg Start pt:', str(leg['start_location'])
      if leg['start_location'] not in pointslist:
        pointslist.append(leg['start_location'])
      for step in leg['steps']:
        if verbose: print ' Step ##', stepcnt
        if verbose: print '   distance:', step['distance']['text']
        if verbose: print '   Step start point:', step['start_location']
        if step['start_location'] not in pointslist:
          pointslist.append(step['start_location'])
        if verbose: print '   Step end point:', step['end_location']
        if step['end_location'] not in pointslist:
          pointslist.append(step['end_location'])
        stepcnt += 1
      if verbose: print 'Leg End:', str(leg['end_location'])
      if leg['end_location'] not in pointslist:
        pointslist.append(leg['end_location'])
  return pointslist

def load_cloudmade_json(filename):
  print 'Parsing json from', filename
  cmjson = open(filename, 'r')
  return json.load(cmjson)['route_geometry']
  cmjson.close
  pass

def generate_map(points):
  PART0 = """
  <!doctype html>
  <html>
    <head>
      <meta name="viewport" content="initial-scale=1, user-scalable=no" />
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <style type="text/css">
        html,body {height:100%; margin:0; padding:0;}
        #map_canvas {width:100%; height:100%;}
      </style>
      <script type="text/javascript"
        src="http://maps.google.com/maps/api/js?sensor=false">
      </script>
      <script type="text/javascript">
        function initialize() {
          var latlon = new google.maps.LatLng(50.18, 30.28);
          var myOpts = {
            zoom: 9,
            center: latlon,
            mapTypeId: google.maps.MapTypeId.ROADMAP
          };
          var map = new google.maps.Map(document.getElementById("map_canvas"),
                                        myOpts);
          var driveTrackCoords = [
  """
  PART1 = """
          ];
          var myPath = new google.maps.Polyline({
            path: driveTrackCoords,
            strokeColor: "#000000",
            strokeOpacity: 1.0,
            strokeWeight: 3
          });
          myPath.setMap(map);
        }
      </script>
    </head>
    
    <body onload="initialize()">
      <div id="map_canvas"></div>
    </body>
  </html
  """
  print 'Generating map...'
  code = str()
  for point in points:
    code += '          new google.maps.LatLng('
    code += '{}, {}),'.format(point['lat'], point['lon'])
    code += '\n'
  
  out = open('generated_map.html', 'w')
  out.write(PART0 + code + PART1)
  out.close

def calc_dist(lat0, lon0, lat1, lon1):
  RADIUS = 6378137
  lat0 = math.radians(lat0)
  lon0 = math.radians(lon0)
  lat1 = math.radians(lat1)
  lon1 = math.radians(lon1)
  cos_mult = math.cos(lat0) * math.cos(lat1) * math.cos(lon1 - lon0)
  sin_mult = math.sin(lat0) * math.sin(lat1)
  return RADIUS * math.acos(cos_mult + sin_mult)

def generate_track_by_basepoints(bpts, verbose):
  print 'Got {} points. Generating track...'.format(len(bpts))
  generated_pts = bpts[:1]
  if verbose: print ' Initial point is', generated_pts
  for i in range(1,len(bpts)):
    lat0 = bpts[i-1][0]
    lon0 = bpts[i-1][1]
    lat1 = bpts[i][0]
    lon1 = bpts[i][1]
    dist = calc_dist(lat0, lon0, lat1, lon1)
    if verbose: print '  The distance is', dist
    n = dist / 15
    if n > 0:
      if verbose: print '  Insert {} points in Step #{}'.format(int(n), i)
      lat = lat0
      lon = lon0
      lat_inc = (lat1 - lat0) / n
      lon_inc = (lon1 - lon0) / n
      for k in range(int(n)):
        lat += lat_inc
        lon += lon_inc
        generated_pts.append((lat, lon))
    generated_pts.append(bpts[i])
  return generated_pts

  pass

def add_random_speeds_to_points(points, tstmp):
  args = parse_args()
  if args.lsp: sp0 = args.lsp
  else: sp0 = 40
  if args.usp: sp1 = args.usp
  else: sp1 = 60
  pts_with_speeds = []
  for pt in points:
    speed = random.randint(sp0, sp1)
    tstmp += 15 * 3.6 / speed
    newpt = {
      'lat':pt[0],
      'lon':pt[1],
      'speed':speed,
      'timestamp':time.strftime('%Y-%m-%d %H:%M:%S %z', time.gmtime(tstmp)),
      'direction':0
    }
    pts_with_speeds.append(newpt)
  return pts_with_speeds
  pass

def gen_track_by_cm_json(f, t, v):
  bspts = load_cloudmade_json(f)
  if v: print '\n Got {} unique points:'.format(len(bspts))
  if v:
    for pt in bspts: print pt
  gp = generate_track_by_basepoints(bspts, v)
  print '\n Generated {} point track :'.format(len(gp))
  return add_random_speeds_to_points(gp, t)

def write_to_file(filename, data):
  f = open(filename, 'w')
  f.write(json.dumps({'locations':data}))
  f.close

def post_points():
  pass

def main():
  if len(sys.argv) == 1:
    print '\n	No arguments passed. Use --help for help\n'
    return
  args = parse_args()
  v = args.verbose
  
  if args.time:
    stime = time.mktime(time.strptime(args.time, '%Y-%m-%d %H:%M:%S'))
  else:
    stime = time.time()
  
  points = gen_track_by_cm_json(args.filename, stime, v)
  
  if args.gen_map:
    generate_map(points)
  if args.out:
    write_to_file(args.out, points)
  else:
    write_to_file('generated_cp_track.json', points)
  pass

if __name__ == '__main__':
  main()
