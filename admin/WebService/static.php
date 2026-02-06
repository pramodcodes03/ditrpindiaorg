<?php 
// headers to tell that result is JSON
header('Content-type: application/json');

//$imgUrl = "https://localhost:1080/uploads/mobileapp/tutorials/";
$imgUrl = "https://ditrpindia.com/uploads/mobileapp/tutorials/";
$output= array(
	'1'=>array(
		"image"=>$imgUrl.'1.jpg',
		"title"=>"Computer case",
		"description"=>html_entity_decode("The basic parts of a desktop computer are the computer
case, monitor, keyboard, mouse, and power cord. Each part plays
an important role whenever you use a computer.
Watch the video below to learn about the basic parts of a desktop
computer. The computer case is the metal and plastic box that contains the main components of the computer, including the motherboard, central processing unit (CPU), and power supply. The front of the case usually has an On/Off button and one or more optical drives.
Computer cases come in different shapes and sizes. A desktop case lies flat on a desk, and the monitor usually sits on top of it. A tower case is tall and sits next to the monitor or on the floor. All-in-one computers come with the internal components built into the monitor, which eliminates the need for a separate case.")),
	
	'2'=>array(
		"image"=>$imgUrl.'2.jpg',
		"title"=>"Monitor",
		"description"=>html_entity_decode("The monitor works with a video card, located inside the computer case, to display images and text on the screen. Most monitors have control buttons that allow you to change your monitor's display settings, and some monitors also have built-in speakers.
Newer monitors usually have LCD (liquid crystal display) or LED (light-emitting diode) displays. These can be made very thin, and they are often called flat-panel displays. Older monitors use CRT (cathode ray tube) displays. CRT monitors are much larger and heavier, and they take up more desk space.
")
	),

	'3'=>array(
		"image"=>$imgUrl.'8.jpg',
		"title"=>"Keyboard",
		"description"=>html_entity_decode("The keyboard is one of the main ways to communicate with a computer. There are many different types of keyboards, but most are very similar and allow you to accomplish the same basic tasks.
Click the buttons in the interactive below to learn about the different parts of the keyboard.
Introduction
Take a look at the front and back of your computer case and count the number of buttons, ports, and slots you see. Now look at your monitor and count any you find there. You probably counted at least 10, and maybe a lot more.
Each computer is different, so the buttons, ports, and sockets will vary from computer to computer. However, there are certain ones you can expect to find on most desktop computers. Learning how these ports are used will help whenever you need to connect something to your computer, like a new printer, keyboard, or mouse.
Watch the video below to learn about the buttons, ports, and slots on a desktop computer.
")
	),

	'4'=>array(
		"image"=>$imgUrl.'3.jpg',
		"title"=>"Front of a computer case",
		"description"=>html_entity_decode("Click the buttons in the interactive below to become familiar with the front of a computer.")
	),

	'5'=>array(
		"image"=>$imgUrl.'4.jpg',
		"title"=>"Back of a computer case",
		"description"=>html_entity_decode("Click the buttons in the interactive below to become familiar with the front of a computer.")
	),

	'6'=>array(
		"image"=>'',
		"title"=>"OTHER TYPES OF PORTS",
		"description"=>html_entity_decode("There are many other types of ports, such as FireWire, Thunderbolt, and HDMI. If your computer has ports you don't recognize, you should consult your manual for more information.
Now you try it! Practice connecting the cables with the interactive game below.
Peripherals you can use with your computer
The most basic computer setup usually includes the computer case, monitor, keyboard, and mouse, but you can plug many different types of devices into the extra ports on your computer. These devices are called peripherals. Let's take a look at some of the most common ones.")
	),

	'7'=>array(
		"image"=>$imgUrl.'5.jpg',
		"title"=>"Printer",
		"description"=>html_entity_decode("A printer is used to print documents, photos, and anything else that appears on your screen. There are many types of printers, including inkjet, laser, and photo printers. There are even all-in-one printers, which can also scan and copy documents.")
	),

	'8'=>array(
		"image"=>'',
		"title"=>"Scanners",
		"description"=>html_entity_decode("A scanner allows you to copy a physical image or document and save it to your computer as a digital (computer-readable) image. Many scanners are included as part of an all-in-one printer, although you can also buy a separate flatbed or handheld scanner.")
	),

	'9'=>array(
		"image"=>$imgUrl.'6.jpg',
		"title"=>"Speakers/headphones",
		"description"=>html_entity_decode("Speakers and headphones are output devices, which means they send information from the computer to the user—in this case, they allow you to hear sound and music. Depending on the model, they may connect to the audio port or the USB port. Some monitors also have built-in speakers.")
	),

	'10'=>array(
		"image"=>'',
		"title"=>"Microphones",
		"description"=>html_entity_decode("A microphone is a type of input device, or a device that receives information from a user. You can connect a microphone to record sound or talk with someone else over the Internet. Many laptop computers come with built-in microphones.")
	),

	'11'=>array(
		"image"=>$imgUrl.'7.jpg',
		"title"=>"Web cameras",
		"description"=>html_entity_decode("A web camera—or webcam—is a type of input device that can record videos and take pictures. It can also transmit video over the Internet in real time, which allows for video chat or video conferencing with someone else. Many webcams also include a microphone for this reason.")
	),

	'12'=>array(
		"image"=>'',
		"title"=>"Game controllers and joysticks",
		"description"=>html_entity_decode("A game controller is used to control computer games. There are many other types of controllers you can use, including joysticks, although you can also use your mouse and keyboard to control most games.")
	),

	'13'=>array(
		"image"=>'30.jpg',
		"title"=>"Digital cameras",
		"description"=>html_entity_decode("A digital camera lets you capture pictures and videos in a digital format. By connecting the camera to your computer's USB port, you can transfer the images from the camera to the computer. Mobile phones, MP3 players, tablet computers, and other devices: Whenever you buy an electronic device, such as a mobile phone or MP3 player, check to see if it comes with a USB cable. If it does, this means you can most likely connect it to your computer. If you want to learn how to type or improve your touch-typing skills, check out our free Typing Tutorial.")
	),

	'14'=>array(
		"image"=>$imgUrl.'9.jpg',
		"title"=>"Mouse",
		"description"=>html_entity_decode("The mouse is another important tool for communicating with computers. Commonly known as a pointing device, it lets you point to objects on the screen, click on them, and move them.
There are two main mouse types: optical and mechanical. The optical mouse uses an electronic eye to detect movement and is easier to clean. The mechanical mouse uses a rolling ball to detect movement and requires regular cleaning to work properly.
To learn the basics of using a mouse, check out our interactive Mouse Tutorial.
")
	),

	'15'=>array(
		"image"=>'',
		"title"=>"Mouse alternatives",
		"description"=>html_entity_decode("There are other devices that can do the same thing as a mouse. Many people find them easier to use, and they also require less desk space than a traditional mouse. The most common mouse alternatives are below.")),

	'16'=>array(
		"image"=>$imgUrl.'10.jpg',
		"title"=>"Trackball",
		"description"=>html_entity_decode("A trackball has a ball that can rotate freely. Instead of moving the device like a mouse, you can roll the ball with your thumb to move the pointer.")
	),

	'17'=>array(
		"image"=>$imgUrl.'11.jpg',
		"title"=>"Touchpad",
		"description"=>html_entity_decode("A touchpad—also called a trackpad—is a touch-sensitive pad that lets you control the pointer by making a drawing motion with your finger. Touchpads are common on laptop computers.")
	),

	'18'=>array(
		"image"=>'',
		"title"=>"INSIDE A COMPUTER",
		"description"=>html_entity_decode("Have you ever looked inside a computer case, or seen pictures of the inside of one? The small parts may look complicated, but the inside of a computer case isn't really all that mysterious. This lesson will help you master some of the basic terminology and understand a bit more about what goes on inside a computer.
Watch the video below to learn about what's inside a desktop computer.
")
	),

	'19'=>array(
		"image"=>$imgUrl.'12.jpg',
		"title"=>"Motherboard",
		"description"=>html_entity_decode("The motherboard is the computer's main circuit board. It's a thin plate that holds the CPU, memory, connectors for the hard drive and optical drives, expansion cards to control the video and audio, and connections to your computer's ports (such as USB ports). The motherboard connects directly or indirectly to every part of the computer.")
	),
	'20'=>array(
		"image"=>$imgUrl.'13.jpg',
		"title"=>"CPU/processor",
		"description"=>html_entity_decode("The central processing unit (CPU), also called a processor, is located inside the computer case on the motherboard. It is sometimes called the brain of the computer, and its job is to carry out commands. Whenever you press a key, click the mouse, or start an application, you're sending instructions to the CPU.
The CPU is usually a two-inch ceramic square with a silicon chip located inside. The chip is usually about the size of a thumbnail. The CPU fits into the motherboard's CPU socket, which is covered by the heat sink, an object that absorbs heat from the CPU.
A processor's speed is measured in megahertz (MHz), or millions of instructions per second; and gigahertz (GHz), or billions of instructions per second. A faster processor can execute instructions more quickly. However, the actual speed of the computer depends on the speed of many different components—not just the processor.
")
	),
	'21'=>array(
		"image"=>$imgUrl.'14.jpg',
		"title"=>"RAM (random access memory)",
		"description"=>html_entity_decode("RAM is your system's short-term memory. Whenever your computer performs calculations, it temporarily stores the data in the RAM until it is needed.
This short-term memory disappears when the computer is turned off. If you're working on a document, spreadsheet, or other type of file, you'll need to save it to avoid losing it. When you save a file, the data is written to the hard drive, which acts as long-term storage.
RAM is measured in megabytes (MB) or gigabytes (GB). The more RAM you have, the more things your computer can do at the same time. If you don't have enough RAM, you may notice that your computer is sluggish when you have several programs open. Because of this, many people add extra RAM to their computers to improve performance.
")
	),
	'22'=>array(
		"image"=>$imgUrl.'15.jpg',
		"title"=>"Hard drive",
		"description"=>html_entity_decode("The hard drive is where your software, documents, and other files are stored. The hard drive is long-term storage, which means the data is still saved even if you turn the computer off or unplug it.
When you run a program or open a file, the computer copies some of the data from the hard drive onto the RAM. When you save a file, the data is copied back to the hard drive. The faster the hard drive, the faster your computer can start up and load programs.
")
	),
	'23'=>array(
		"image"=>$imgUrl.'16.jpg',
		"title"=>"Power supply unit",
		"description"=>html_entity_decode("The power supply unit in a computer converts the power from the wall outlet to the type of power needed by the computer. It sends power through cables to the motherboard and other components.
If you decide to open the computer case and take a look, make sure to unplug the computer first. Before touching the inside of the computer, you should touch a grounded metal object—or a metal part of the computer casing—to discharge any static buildup. Static electricity can be transmitted through the computer circuits, which can seriously damage your machine.
")
	),
	'24'=>array(
		"image"=>'',
		"title"=>"Expansion cards",
		"description"=>html_entity_decode("Most computers have expansion slots on the motherboard that allow you to add various types of expansion cards. These are sometimes called PCI (peripheral component interconnect) cards. You may never need to add any PCI cards because most motherboards have built-in video, sound, network, and other capabilities.
However, if you want to boost the performance of your computer or update the capabilities of an older computer, you can always add one or more cards. Below are some of the most common types of expansion cards.
")
	),
	'25'=>array(
		"image"=>$imgUrl.'17.jpg',
		"title"=>"Video card",
		"description"=>html_entity_decode("The video card is responsible for what you see on the monitor. Most computers have a GPU (graphics processing unit) built into the motherboard instead of having a separate video card. If you like playing graphics-intensive games, you can add a faster video card to one of the expansion slots to get better performance.
")
	),
	'26'=>array(
		"image"=>'',
		"title"=>"Sound card",
		"description"=>html_entity_decode("The sound card—also called an audio card—is responsible for what you hear in the speakers or headphones. Most motherboards have integrated sound, but you can upgrade to a dedicated sound card for higher-quality sound.
")
	),
	'27'=>array(
		"image"=>$imgUrl.'18.jpg',
		"title"=>"Network card",
		"description"=>html_entity_decode("The network card allows your computer to communicate over a network and access the Internet. It can either connect with an Ethernet cable or through a wirelessconnection (often called Wi-Fi). Many motherboards have built-in network connections, and a network card can also be added to an expansion slot.
")
	),
	'28'=>array(
		"image"=>$imgUrl.'19.jpg',
		"title"=>"Bluetooth card (or adapter)",
		"description"=>html_entity_decode("Bluetooth is a technology for wireless communication over short distances. It's often used in computers to communicate with wireless keyboards, mice, and printers. It's commonly built into the motherboard or included in a wireless network card. For computers that don't have Bluetooth, you can purchase a USB adapter, often called a dongle.
")
	),
	'29'=>array(
		"image"=>'',
		"title"=>"WHAT IS A LAPTOP COMPUTER?",
		"description"=>html_entity_decode("A laptop is a personal computer that can be easily moved and used in a variety of locations. Most laptops are designed to have all of the functionality of a desktop computer, which means they can generally run the same software and open the same types of files. However, laptops also tend to be more expensive than comparable desktop computers.
Watch the video below to learn about laptop computers.
")
	),
	
	'30'=>array(
		"image"=>'',
		"title"=>"How is a laptop different from a desktop?",
		"description"=>html_entity_decode("Because laptops are designed for portability, there are some important differences between them and desktop computers. A laptop has an all-in-one design, with a built-in monitor, keyboard, touchpad (which replaces the mouse), and speakers. This means it is fully functional, even when no peripherals are connected. A laptop is also quicker to set up, and there are fewer cables to get in the way.
You'll also have to the option to connect a regular mouse, larger monitor, and other peripherals. This basically turns your laptop into a desktop computer, with one main difference: You can easily disconnect the peripherals and take the laptop with you wherever you go.
Here are the main differences you can expect with a laptop.
")
	),
	'31'=>array(
		"image"=>$imgUrl.'20.jpg',
		"title"=>"Touchpad",
		"description"=>html_entity_decode("A touchpad—also called a trackpad—is a touch-sensitive pad that lets you control the pointer by making a drawing motion with your finger.")
	),
	'32'=>array(
		"image"=>'',
		"title"=>"Battery",
		"description"=>html_entity_decode("Every laptop has a battery, which allows you to use the laptop when it's not plugged in. Whenever you plug in the laptop, the battery recharges. Another benefit of having a battery is that it can provide backup power to the laptop if the power goes out.
")
	),
	'33'=>array(
		"image"=>$imgUrl.'21.jpg',
		"title"=>"AC adapter",
		"description"=>html_entity_decode("A laptop usually has a specialized power cable called an AC adapter, which is designed to be used with that specific type of laptop.
")
	),
	'34'=>array(
		"image"=>'',
		"title"=>"Ports",
		"description"=>html_entity_decode("Most laptops have the same types of ports found on desktop computers (such as USB), although they usually have fewer ports to save space. However, some ports may be different, and you may need an adapter in order to use them.
")
	),
	
	'35'=>array(
		"image"=>$imgUrl.'35.jpg',
		"title"=>"Price",
		"description"=>html_entity_decode("Generally speaking, laptops tend to be more expensive than a desktop computer with the same internal components. While you may find that some basic laptops cost less than desktop computers, these are usually much less powerful machines.
")
	),
	'36'=>array(
		"image"=>'',
		"title"=>"WHAT IS A MOBILE DEVICE?",
		"description"=>html_entity_decode("A mobile device is a general term for any type of handheld computer. These devices are designed to be extremely portable, and they can often fit in your hand. Some mobile devices—like tablets, e-readers, and smartphones—are powerful enough to do many of the same things you can do with a desktop or laptop computer.
")
	),
	'37'=>array(
		"image"=>$imgUrl.'22.jpg',
		"title"=>"TABLET COMPUTERS",
		"description"=>html_entity_decode("Like laptops, tablet computers are designed to be portable. However, they provide a different computing experience. The most obvious difference is that tablet computers don't have keyboards or touchpads. Instead, the entire screen is touch-sensitive, allowing you to type on a virtual keyboard and use your finger as a mouse pointer.
		Tablet computers can't necessarily do everything traditional computers can do. For many people, a traditional computer like a desktop or laptop is still needed in order to use some programs. However, the convenience of a tablet computer means it may be ideal as a second computer.
")
	),
	'38'=>array(
		"image"=>$imgUrl.'23.jpg',
		"title"=>"E-READERS",
		"description"=>html_entity_decode("E-book readers—also called e-readers—are similar to tablet computers, except they are mainly designed for reading e-books (digital, downloadable books). Notable examples include the Amazon Kindle, Barnes & Noble Nook, and Kobo. Most e-readers use an e-ink display, which is easier to read than a traditional computer display. You can even read in bright sunlight, just like if you were reading a regular book.
		You don't need an e-reader to read e-books. They can also be read on tablets, smartphones, laptops, and desktops.
")
	),
	'39'=>array(
		"image"=>$imgUrl.'24.jpg',
		"title"=>"SMARTPHONES",
		"description"=>html_entity_decode("A smartphone is a more powerful version of a traditional cell phone. In addition to the same basic features—phone calls, voicemail, text messaging—smartphones can connect to the Internet over Wi-Fi or a cellular network (which requires purchasing a monthly data plan). This means you can use a smartphone for the same things you would normally do on a computer, such as checking your email, browsing the Web, or shopping online.
		Most smartphones use a touch-sensitive screen, meaning there isn't a physical keyboard on the device. Instead, you'll type on a virtual keyboard and use your fingers to interact with the display. Other standard features include a high-quality digital camera and the ability to play digital music and video files. For many people, a smartphone can actually replace electronics like an old laptop, digital music player, and digital camera in the same device
")
	),
	'40'=>array(
		"image"=>'',
		"title"=>"What is an operating system?",
		"description"=>html_entity_decode("An operating system is the most important software that runs on a computer. It manages the computer's memory and processes, as well as all of its software and hardware. It also allows you to communicate with the computer without knowing how to speak the computer's language. Without an operating system, a computer is useless.
Watch the video below to learn more about operating systems.
")
	),
	'41'=>array(
		"image"=>'',
		"title"=>"The operating system's job",
		"description"=>html_entity_decode("Your computer's operating system (OS) manages all of the software and hardware on the computer. Most of the time, there are several different computer programs running at the same time, and they all need to access your computer's central processing unit (CPU), memory, and storage. The operating system coordinates all of this to make sure each program gets what it needs.
")
	),
	'42'=>array(
		"image"=>$imgUrl.'25.jpg',
		"title"=>"TYPES OF OPERATING SYSTEMS",
		"description"=>html_entity_decode("Operating systems usually come pre-loaded on any computer you buy. Most people use the operating system that comes with their computer, but it's possible to upgrade or even change operating systems. The three most common operating systems for personal computers are Microsoft Windows, Mac OS X, and Linux.
Modern operating systems use a graphical user interface, or GUI (pronounced gooey). A GUI lets you use your mouse to click icons, buttons, and menus, and everything is clearly displayed on the screen using a combination of graphics and text.
Each operating system's GUI has a different look and feel, so if you switch to a different operating system it may seem unfamiliar at first. However, modern operating systems are designed to be easy to use, and most of the basic principles are the same.
")
	),
	'43'=>array(
		"image"=>$imgUrl.'26.jpg',
		"title"=>"MICROSOFT WINDOWS",
		"description"=>html_entity_decode("Microsoft created the Windows operating system in the mid-1980s. Over the years, there have been many different versions of Windows, but the most recent ones are Windows 10 (released in 2015), Windows 8 (2012), Windows 7 (2009), and Windows Vista (2007). Windows comes pre-loaded on most new PCs, which helps to make it the most popular operating system in the world.
		Check out our tutorials on Windows Basics and specific Windows versions for more information.
")
	),	
	'44'=>array(
		"image"=>$imgUrl.'27.jpg',
		"title"=>"Mac OS X",
		"description"=>html_entity_decode("Mac OS is a line of operating systems created by Apple. It comes preloaded on all new Macintosh computers, or Macs. All of the recent versions are known as OS X (pronounced O-S Ten), and the specific versions include El Capitan (released in 2015),Yosemite (2014), Mavericks (2013), Mountain Lion (2012), and Lion (2011).
According to StatCounter Global Stats, Mac OS X users account for less than 10% of global operating systems—much lower than the percentage of Windows users (more than 80%). One reason for this is that Apple computers tend to be more expensive. However, many people do prefer the look and feel of Mac OS X over Windows.
Check out our tutorials on OS X Basics and specific OS X versions for more information.
")
	),
	'45'=>array(
		"image"=>$imgUrl.'28.jpg',
		"title"=>"Linux",
		"description"=>html_entity_decode("Linux (pronounced LINN-ux) is a family of open-source operating systems, which means they can be modified and distributed by anyone around the world. This is different from proprietary software like Windows, which can only be modified by the company that owns it. The advantages of Linux are that it is free, and there are many different distributions—or versions—you can choose from.
According to StatCounter Global Stats, Linux users account for less than 2% of global operating systems. However, most servers run Linux because it's relatively easy to customize.
To learn more about different distributions of Linux, visit the Ubuntu, Linux Mint, and Fedora websites, or refer to our Linux Mint Resources. For a more comprehensive list, you can visit MakeUseOf's list of The Best Linux Distributions.
")
	),
	'46'=>array(
		"image"=>$imgUrl.'29.jpg',
		"title"=>"Operating systems for mobile devices",
		"description"=>html_entity_decode("The operating systems we've been talking about so far were designed to run on desktop and laptop computers. Mobile devices such as phones, tablet computers, and MP3 players are different from desktop and laptop computers, so they run operating systems that are designed specifically for mobile devices. Examples of mobile operating systems include Apple iOS and Google Android. In the screenshot below, you can see iOS running on an iPad.
		Operating systems for mobile devices generally aren't as fully featured as those made for desktop and laptop computers, and they aren't able to run all of the same software. However, you can still do a lot of things with them, like watch movies, browse the Web, manage your calendar, and play games.
To learn more about mobile operating systems, check out our Mobile Devicestutorials.
"))
	
);
echo  json_encode($output);


?>

