
 ╔═════════════════════════════════════════╗
 ║                                         ║
 ║   pChart - a PHP Charting library       ║
 ║                                         ║
 ║   Version     : 2.0.8                   ║
 ║   Made by     : Jean-Damien POGOLOTTI   ║
 ║   Last Update : 14/12/10                ║
 ║                                         ║
 ╚═════════════════════════════════════════╝

 ┬
 │
 ├─ /cache			This folder is used by the pCache module.
 │
 ├─ /class			This folder contains the library core classes.
 │   │
 │   ├─ pBarcode39.class	Class to draw Code 39 barcodes.
 │   ├─ pBarcode128.class	Class to draw Code 128 barcodes.
 │   ├─ pCache.class		Class enable chart caching functionalities.
 │   ├─ pData.class		Class to manipulate chart data.
 │   ├─ pDraw.class		Extended drawing functions.
 │   ├─ pImage.class		Core drawing functions.
 │   ├─ pPie.class		Class to draw pie charts.
 │   ├─ pSplit.class		Class to draw split path charts.
 │   └─ pSpring.class		Class to draw spring charts.
 │
 ├─ /data			This folder contains extended data.
 │   │
 │   ├─ 39.db			Code 39 barcodes static database.
 │   └─ 128.db			Code 128 barcodes static database.
 │
 ├─ /examples			This folder contains some PHP examples.
 │
 └─ /fonts			This folder contains some TTF fonts


 PREREQUISITES ────────────────────────────────────────────────────────────────

  This library has been written to work with PHP 5+ versions. It will also work
  with PHP 4 but the rendering quality maybe downgraded and the rendering speed
  seriously impacted.

  pChart require the GD and FreeType PHP extensions to be installed on your
  web server. This is an important prerequiste that can't be overrided.  


 WHAT CAN pCHART DO FOR YOU? ──────────────────────────────────────────────────

  pChart is a PHP framework that will help you to create anti-aliased charts or
  pictures directly from your web server. You can then display the result in
  the client browser, sent it by mail or insert it into PDFs. 

  This library has now reached an important point in its development cycle
  going out of the beta step. pChart 2.0 is a completly rewritten library based
  on what I've learned doing the first version. 


 RUNNING THE EXAMPLES ─────────────────────────────────────────────────────────

  pChart is shipped with examples (located in the /examples folder) that you
  can either render from a web page using your http and pointing to this folder
  or from the command line invoking the php interpreter.

  On windows OS, assuming that your PHP binaries are correctly configured in
  the PATH environment variable you can also execute the BuildAll.cmd batch
  file.

 LICENSE ──────────────────────────────────────────────────────────────────────

  The pChart library is released under two different licenses. If your
  application is not a commercial one (eg: you make no money by redistributing
  it) then the GNU GPLv3 license (General Public License) applies. This license
  allows you to freely integrate this library in your applications, modify the
  code and redistribute it in bundled packages as long as your application is
  also distributed with the GPL license. 

  The GPLv3 license description can be found in GPLv3.txt.

  If your application can't meet the GPL license or is a commercial one (eg:
  the library is integrated in a software or an appliance you're selling) then
  you'll have to buy a commercial license. With this license you don't need to
  make publicly available your application code under the GPL license terms.
  Commercial license price are depending of your needs

  Please consult the web page : http://www.pchart.net/license


 SUPPORT ──────────────────────────────────────────────────────────────────────

  Since the beginning, pChart is a community driven project. You're missing
  feature then ask! We'll try to get it implemented in the future version or
  you'll be guided to create a class extension for your own needs. 

  All the library classes and methods are explained on the pWiki pages. Just
  click on the documentation link on top of this page. 

