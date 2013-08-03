wb-compiler
===========

This is a sub project of [Wisebender](https://github.com/pcoder/wisebender.git "Wisebender") and comprises of a single php script file. The script in this project, picks up the projects created on the Wisebender server and compiles them according to various parameters.

The script accepts JSON request in the following form:

    
    POST /compiler.php

### Parameters ###

####format####
*String* of the format of the output to be obtained from the script. For now, the only supported format assumed is `binary`.

####build####
*String* of the name of the build platform, like: iSense, Shawn, arduino, iOS, android, etc. For now, the only supported build name is `isense`.

####username####
*String* of the name of the user on the Wisebender platform who requested for the build.


####wiselibUUID####
*String* of the *UUID* that represents the project under compilation.


####files####
*Object* of the list of the source file of the Wiselib Application under compilation.


####output####
*String* (base64 representation) of the compiled artifact. This needs to decoded back from base64.

### Input ###
    {
    	"format":"binary",
    	"build":"isense",
    	"username":"amaxilatis",
    	"wiselibUUID":"151e1352e5f72c",
    	"files":[{"filename":"test_app.cpp",
    			  "content":"/*\n* Simple Wiselib Example\n*/\n\n#include 		\"external_interface/external_interface.h\"\n#include \"algorithms/routing/tree/tree_routing.h\"\n\ntypedef wiselib::OSMODEL Os;\n\nclass ExampleApplication\n{\npublic:\n\nvoid init( Os::AppMainParameter& value )\n{\n   \t\nradio_ = &wiselib::FacetProvider<Os, Os::Radio>::get_facet( value );\ntimer_ = &wiselib::FacetProvider<Os, Os::Timer>::get_facet( value );\ndebug_ = &wiselib::FacetProvider<Os, Os::Debug>::get_facet( value );\n\nradio_->enable_radio();\n\ndebug_->debug( \"Hello World from Example Application!\\n\" );\n\nradio_->reg_recv_callback<ExampleApplication,\n&ExampleApplication::receive_radio_message>( this );\ntimer_->set_timer<ExampleApplication,\n&ExampleApplication::start>( 5000, this, 0 );\n}\n// --------------------------------------------------------------------\nvoid start( void* )\n{\ndebug_->debug( \"broadcast message at %d \\n\", radio_->id() );\nOs::Radio::block_data_t message[] = \"hello!\\0\";\nradio_->send( Os::Radio::BROADCAST_ADDRESS, sizeof(message), message );\n\n// following can be used for periodic messages to sink\ntimer_->set_timer<ExampleApplication,\n&ExampleApplication::start>( 5000, this, 0 );\n}\n\n// --------------------------------------------------------------------\nvoid receive_radio_message( Os::Radio::node_id_t from, Os::Radio::size_t len, Os::Radio::block_data_t *buf )\n{\ndebug_->debug( \"received msg at %x from %x\", radio_->id(), from );\ndebug_->debug( \"  message is %s\\n\", buf );\n}\n\nprivate:\nOs::Radio::self_pointer_t radio_;\nOs::Timer::self_pointer_t timer_;\nOs::Debug::self_pointer_t debug_;\n};\n\n// --------------------------------------------------------------------------\nwiselib::WiselibApplication<Os, ExampleApplication> example_app;\n// --------------------------------------------------------------------------\n\nvoid application_main( Os::AppMainParameter& value )\n{\nexample_app.init( value );\n}\n"
    			  }]
    }



### Response ###

    Array
    (
    [success] => 1
    [size] => 65012
    [message] => make -f /wisebender/sketches/amaxilatis/151e1352e5f72c//apps/generic_apps/Makefile.isense JENNIC_CHIP=JN5139R1 ADD_CXXFLAGS=<br/>make[1]: Entering directory `/compiler/app'<br/>compiling...<br/>/opt/ba-elf/bin/ba-elf-g++ -DPCB_DEVKIT2 -DEMBEDDED -DLEAN_N_MEAN -DSINGLE_CONTEXT -DCHIP_RELEASE_5131 -I. -DOR1K -Wall -fomit-frame-pointer -fno-strength-reduce -g -pipe  -DEMBEDDED -DLEAN_N_MEAN -DSINGLE_CONTEXT -Os -fno-builtin -nostdlib -msibcall -mno-entri -mno-multi -mno-setcc -mno-cmov -mno-carry -mno-subb -mno-sext -mno-ror -mno-ff1 -mno-hard-div -mhard-mul -mbranch-cost=3 -msimple-mul -mabi=1 -march=ba1 -mredzone-size=4 -DPCB_DEVKIT2 -ffunction-sections -fdata-sections -fno-exceptions -fconserve-space -fno-implicit-inline-templates -fno-rtti  -I/compiler/iSense_typical//lib/jennic/1v4/Common/Include -I/compiler/iSense_typical//lib/jennic/1v4/Chip/JN513xR1/Include -I/compiler/iSense_typical//lib/jennic/1v4/Platform/DK2/Include -I/compiler/iSense_typical//lib/jennic/1v4/Platform/Common/Include -I/compiler/iSense_typical//src -I/wisebender/sketches/amaxilatis/151e1352e5f72c//wiselib.testing -I/wisebender/sketches/amaxilatis/151e1352e5f72c//wiselib.stable -DISENSE_JENNIC -DISENSE_JENNIC_JN513xR1 -DNDEBUG -DOSMODEL=iSenseOsModel -DISENSE -Os -finline-limit=40 -fno-strength-reduce -pipe -fno-builtin -nostdlib -g -DCHIP_RELEASE_3 -fno-exceptions -fconserve-space -fno-implicit-inline-templates -fno-rtti  -MMD -c /wisebender/sketches/amaxilatis/151e1352e5f72c//wiselib.stable/external_interface/isense/isense_os_standalone.cpp -o out/isense/isense_os_standalone.o<br/>/opt/ba-elf/bin/ba-elf-g++ -DPCB_DEVKIT2 -DEMBEDDED -DLEAN_N_MEAN -DSINGLE_CONTEXT -DCHIP_RELEASE_5131 -I. -DOR1K -Wall -fomit-frame-pointer -fno-strength-reduce -g -pipe  -DEMBEDDED -DLEAN_N_MEAN -DSINGLE_CONTEXT -Os -fno-builtin -nostdlib -msibcall -mno-entri -mno-multi -mno-setcc -mno-cmov -mno-carry -mno-subb -mno-sext -mno-ror -mno-ff1 -mno-hard-div -mhard-mul -mbranch-cost=3 -msimple-mul -mabi=1 -march=ba1 -mredzone-size=4 -DPCB_DEVKIT2 -ffunction-sections -fdata-sections -fno-exceptions -fconserve-space -fno-implicit-inline-templates -fno-rtti  -I/compiler/iSense_typical//lib/jennic/1v4/Common/Include -I/compiler/iSense_typical//lib/jennic/1v4/Chip/JN513xR1/Include -I/compiler/iSense_typical//lib/jennic/1v4/Platform/DK2/Include -I/compiler/iSense_typical//lib/jennic/1v4/Platform/Common/Include -I/compiler/iSense_typical//src -I/wisebender/sketches/amaxilatis/151e1352e5f72c//wiselib.testing -I/wisebender/sketches/amaxilatis/151e1352e5f72c//wiselib.stable -DISENSE_JENNIC -DISENSE_JENNIC_JN513xR1 -DNDEBUG -DOSMODEL=iSenseOsModel -DISENSE -Os -finline-limit=40 -fno-strength-reduce -pipe -fno-builtin -nostdlib -g -DCHIP_RELEASE_3 -fno-exceptions -fconserve-space -fno-implicit-inline-templates -fno-rtti  -MMD -c ./app.cpp -o out/isense/app.o<br/>linking...<br/>/opt/ba-elf/bin/ba-elf-ld  -T/compiler/iSense_typical//lib/jennic/1v4/Chip/JN513xR1/Build/AppBuild_JN5139R1.ld -Map out/isense/Map.txt --gc-sections -z muldefs -u _AppWarmStart -u _AppColdStart -o out/isense/app.elf  \<br/>		out/isense/isense_os_standalone.o out/isense/app.o  /compiler/iSense_typical//lib/jennic/ba-elf-float-double-moddiv.a /compiler/iSense_typical//lib/jennic/1v4/Chip/JN513xR1/Library/ChipLib.a /compiler/iSense_typical//lib/jennic/1v4/Platform/DK2/Library/BoardLib_JN513xR1.a /compiler/iSense_typical//lib/jennic/iSenseLibraryJN5139R1.a<br/>make hex...<br/>/opt/ba-elf/bin/ba-elf-objcopy -O ihex out/isense/app.elf out/isense/app.hex<br/>/opt/ba-elf/bin/ba-elf-objcopy -O binary out/isense/app.elf out/isense/app.bin<br/>show sizes...<br/>/opt/ba-elf/bin/ba-elf-size out/isense/app.elf<br/>   text	   data	bss	dec	hex	filename<br/>  64888	124	516	  65528	   fff8	out/isense/app.elf<br/>make[1]: Leaving directory `/compiler/app'
    [output] => 4ODg4AQAEAAAAP3EAQQAMQAAAAAEAQ3UAAAB9AQAZIQEAGWw///////////w8PDw//////////////////////////////////////////8AAAAAAAAAAAQAI/AEACR0BAAiwAAAAAAA........AAAAA=
    )
    