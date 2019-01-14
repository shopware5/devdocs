---
title: Get in touch with Project Tango
tags: 
- ODL
- Project Tango

categories:
- dev

authors: [psc]

github_link: blog/_posts/2016-04-07-introducing-project-tango.md
---

<img src="/blog/img/tango.jpg" style="width: 440px;" class="is-float-right" />

Today I want to introduce you to an interesting device we've got for our open device lab - The Project Tango by Google.

On first sight the device looks like a normal tablet, but when you take a look at the back side, you can get a thought of what the device has to offer. In the place where normally the camera of the device is located, you will find several sensors, like a motion tracking camera, a sensor for ambient light and a 3D depth sensor. It also comes along with an accelerometer, a barometer, compass, GPS and a gyroscope. This impressive toolkit enables the device to capture the physical world around it.

The device is powered by a NVIDIA Tegra K1 processor which delivers enough performance for tracking all the sensors and do some advanced image processing. But all the technical features also need lots of energy, so the first thing I discovered was the intensive battery consumption.

<br />

<img src="/blog/img/tango_motion.jpg" style="width: 440px;" class="is-float-left" />

When you start the device, you have several pre-installed applications for testing the different features. I started with the Project Tango Explorer, where you can test the three basic technologies the device has to offer: motion tracking, depth perception and area learning. The Explorer provides some visualisations for the different sensor types. When testing the sensors you immediately recognize how precise and accurate the position and orientation tracking works. This is way better than on common mobile devices. Not only the orientation, but also how it understands its position in space is phenomenal. You can walk with the device across the room and it will always now where you are. This combination enables the device to capture things around it in a whole new way.

<br />
<br />

<img src="/blog/img/tango_measure.gif" style="width: 520px;" class="is-float-right" />

Testing around with the area learning of the Tango, I continued with the measurement application. This is an augmented reality driven app where you can set two or more measurement points in your field of view. The device will visually connect the points and print out the exact distance between them. I was really impressed by this little feature. Not only because of the measurement was so precise, but also of the fact that I could walk down the complete hallway and measure the full length of our new headquarter. After measuring the whole building I continued with the most interesting feature of the Tango device - the constructor. Now things are getting exciting.

<br />
<br />

<img src="/blog/img/tango_preview.gif" style="width: 560px;" class="is-float-left" />

The constructor application uses all the sensors to create a 3D mesh of the space around you. In real time! Not only connecting polygons but also do a basic texturing of the surface. You can walk through the room and around objects to capture every single detail. The device will extend the mesh step by step as you move around. This enables you to scan a whole room in a few minutes. I must confess that the details of the mesh are not as precise as I primary expected, but the fact that the creation is done in real time really flashed me.

When you're done with the capturing you can save the mesh to the SD card and view it in the constructor app. You can virtually move through the created mesh by using the touch screen. All meshes are primary saved as ```.srb``` files, but you can choose to export a mesh to common file types like ```.obj``` or ```.ply```. You can transfer the files to your computer to import them to Unity or use it in other 3D applications.

The features of the device really got me excited. The way of how the device capture objects in real time can bring a whole new level to augmented reality applications and 3D creation. This kind of device can fill a hole in the process of realizing new concepts like <a href="https://vimeo.com/73953211" target="_blank">virtual shopping</a> or <a href="{{ site.url }}/blog/2016/02/11/projects-of-the-first-internal-hackathon-in-2016/#3d-products" target="_blank">visualizing 3D products</a>.

If you want to learn more about Project Tango you should visit the <a href="https://www.google.com/atap/project-tango/" target="_blank">official website</a>.

When you have questions or new ideas for this interesting device, feel free to get in touch on <a href="https://twitter.com/PhilSchuch" target="_blank">twitter</a>.
