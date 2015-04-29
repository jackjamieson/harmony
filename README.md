# harmony
Collaborative playlist creation.

###Server info:
**Linode LAMP Server**

- http://45.56.101.195/
- Serves PHP front end
- Allows uploading to S3
- Runs liquidsoap scripts to create icecast mount points

**EC2 Icecast Server**
- http://54.152.139.27:8000/
- Serves the streaming content to the user
- Manages synchronization


###Technology info:

**Liquidsoap**
- http://savonet.sourceforge.net/index.html
- Scripting language that can generate playlists to send to streaming radio servers

**Icecast**
- http://icecast.org/
- Used to create/manage streaming radio servers
- Can create multiple "mount points", i.e. different playlists at different URLS

###Flow of data:
Client => Linode => Liquidsoap/Upload to S3 => (EC2 Icecast <=> AWS) => Client

###Things to remember:
- Need to change PHP max upload sizes to fit songs ( > 10mb maybe? )
- Permissions for writing .liq files ( needs write access )
- Requires Composer to install AWS packages, or do it by hand
- 
