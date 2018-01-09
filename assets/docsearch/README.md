<h1>Algoia DocSearch custom styling</h1>

<p>This section of the devdocs contains the custom styling for the <a href="https://github.com/algolia/docsearch">Algolia Docsearch</a>.</p>

<h2>Installation</h2>

<p>First make sure you have <code>sass</code> installed on your system. If you haven't installed it already, follow this <a href="http://sass-lang.com/install">guide</a>.</p>

<p>Next resolve the Node.js dependencies using the following command:</p>

<pre><code>npm install
</code></pre>

<p>Now you're ready to modify the SCSS files in the <code>src</code> directory.</p>

<h2>Compiling</h2>

<p>The build script can be called using NPM:</p>

<pre><code>npm run build:css
</code></pre>

<p>The compiled CSS file will be placed in the <code>source/assets/css</code> directory, so it will be automatically copied to the correct destination when compiling the devdocs using the provided commands / scripts.</p>
