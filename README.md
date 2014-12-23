reloadr
=======

A WordPress-Plugin that reloads all pages when a file in a specified place is updated.

## Requirements
### Serverside
* PHP `> 5.4`
    - Anonymous functions
    - Short Syntax for Arrays
* WordPress `> 4.0.0`

### Clientside
* SSE (Server-Sent-Events)
[Further Information on SSE support](http://caniuse.com/#feat=eventsource)

## Installation
1. Click on **Download ZIP**
2. Unzip the directory
3. Move it to your WordPress instance `wp-content/plugins/`
4. In the WP backend go to **Plugins** and click **Activate Plugin**

## Settings
Reloadr adds some settings to the `Settings/General`-Page.

**Watch this directory**: The directory in which reloadr will watch for changes. *Default: The theme directory*

**Ignore these files**: A list of regex that should be ignored. Reloadr checks every files absolute path against alle these regex. The regex should be seperated by a comma followed by a space (`, `) *Default:* `.*\.DS_Store, .*\.git.*`

**Notifications**: *Default:* `false`

## Usage
The WordPress plugin works after you set a directory to watch.

If you want to use it in other projects you can just drop the whole folder in your projects root and include what is shown bellow. It's just an example.

Structure
- index.html
- reloadr/
index.html:
```
    <script>var reloadr = {reloadSource: 'reloadr/source.php?watch_dir=' + encodeURIComponent('../.'), notifications: false};</script>
    <script type="text/javascript" src="reloadr/reloadr.js"></script>
```

## ToDo's
* Maybe refactor all file manipulation and general OS stuff to be a seperate class
* Roll it out to the WordPress-Plugin directory
* Evaluate Inotify integration
