# WordPress Boilerplate Plugin

WordPress Boilerplate is a boilerplate plugin for WordPress that provides a clear structure for the separation of admin and frontend functions, with support for modern PSR-4 autoloading and best practices in plugin development.
## Features

- Separation of admin and frontend areas
- React integration for admin and frontend components
- psr 4 autoloading
- Hook registration for admin and frontend

## Using the setup script:

The project contains a shell script (`replace.sh`) that replaces the placeholders in the plugin framework with user-defined values. The script facilitates the creation of a new plugin based on the boilerplate.

### Steps for use:

1. Make sure that you make the script executable:
   ```bash
   chmod +x replace.sh
2. execute the script:
   ````bash
   ./replace.sh
3. Provide the following information when prompted:
    - Plugin Name: The full name of your plugin (replaces My Plugin Name).
    - Plugin Description: (Replace This is a short description of what the plugin does.)
    - Vendor Name: (Replace MyVendorNamespace)
    - Namespace Name: The namespace of your plugin (replaces MyPluginNamespace).
    - Defined Name: The plugin constant (replaces WP_PLUGIN).
    - Plugin Slug: The slug of your plugin (replaces wp-plugin-name).
    - Plugin Prefix: The prefix for your plugin functions (replaces wp_plugin_name_).
    - Plugin Author Name: (Replace Your Name or Your Company)
    - Plugin Domain: (Replace http://example.com)
4.	The script will replace the placeholders in the files, rename the main plugin file, and adjust the plugin directory accordingly.
5.	Once complete, your plugin’s directory and files will be correctly set up.

## Creating a Release

To package your plugin for distribution, you can use the `build-release` command. This command creates a ZIP archive of your plugin, excluding unnecessary files such as test directories and development dependencies.

### Steps to create a release:

1. Make sure that all necessary dependencies are installed by running:

    ```bash
    composer install
    ```

2. To create a release, simply run the following Composer script:

    ```bash
    composer run-script build-release
    ```

3. The script will:

   - Remove any existing `_dist-release/` directory.
   - Automatically determine the name of your plugin based on the current directory.
   - Copy all plugin files into the `_dist-release/PLUGIN_NAME` directory, excluding unnecessary files such as `.git`, `node_modules`, `tests`, and development-related files like `.gitignore` and `composer.lock`.
   - Navigate to the `_dist-release/` directory.
   - Create a ZIP file named after your plugin, which contains all the relevant files for distribution.

4. The resulting ZIP file can be found in the `_dist-release/` directory, ready for upload or distribution.
