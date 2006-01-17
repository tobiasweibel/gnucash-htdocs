<?php $TITLE = 'Gnucash - Architecture'; ?>

<?php include("include/header.inc"); ?>

      <! BEGIN CONTENT>

      <?php include("include/table_top.inc"); ?>
        architecture overview
      <?php include("include/table_middle.inc"); ?>
        
<h1>Architectural Overview</h1>
GnuCash is written primarily in two languages: C and Scheme.  
The engine/server is written in C primarily for speed, 
portability, stability and historical purposes.  Much of the
day-to-day workhorse code is written in Scheme, primarily
for its power, expressiveness and ease of development.
The user interface is gtk/gnome, some of it done up in C,
some in scheme, and some with the 
GUI designer tool <a href="http://glade.pn.org">glade</a>.

<p>
GnuCash is modular, thereby allowing separate individuals
to maintain, develop and enhance certain modules without
disturbing the overall development.  (Never mind that 
modules help avoid spaghetti code and nasty, ugly hacks).
The interfaces between modules are documented, and, for the 
most part, stable and unchanging.
<a href="images/diagrams/components.png">This block diagram 
shows major components.</a>

<p>GnuCash currently consists of the following modules:

<h2>The Engine</h2>

<p>The <dfn>Engine</dfn> (located under the <code>src/engine</code> 
directory in the
GnuCash codebase) provides an interface for creating, manipulating, and
destroying three basic financial entities: Accounts, Transactions (known
as Ledger Entries in accounting practice), and Splits (known as Journal
Entries). These three entities are the central data structures of the
GnuCash financial data model,
<a href="images/diagrams/structures.png">as illustraded in this diagram.</a>

<p>The Engine code contains no GUI code whatsoever, and is essentially
OS-neutral. It is written entirely in C.

<h3>Query</h3>

<p>The <dfn>Query</dfn> module (<code>src/engine/Query.*</code>) provides an interface
into the engine for finding transactions based on a set of criteria,
such as description, date posted, account membership, etc. Simple
queries can be combined using standard boolean operators.

<h3>File I/O</h3>

<p>The <dfn>File I/O</dfn> module (<code>src/engine/FileIO.*</code>) provides an
interface for reading and writing a set of Accounts, Transactions, and
Splits to a binary file. This module is being deprecated. A text-based
format using scheme forms is being developed as a replacement. This new
module will be separate from the Engine.

<h2>The Register</h2>

<p>The <dfn>Register</dfn> (<code>src/register</code>) implements a ledger-like
GUI that allows the user to dynamically enter dates, prices, memos
descriptions, etc. in an intuitive fashion that should be obvious to
anyone who's used a checkbook register. The code is highly configurable,
allowing the ledger columns and rows to be laid out in any way, with no
restrictions on the function, type, and number of columns/rows. For
example, one can define a ledger with three date fields, one price
field, and four memo fields in a straightforward fashion. Cell handling
objects support and automatically validate date entry, memo entry
(w/auto-completion), prices, combo-boxes (pull-down menus), and
multi-state check-boxes. Cells can be marked read-write, or
output-only. Cells can be assigned unique colors. The currently
active ledger row-block can be highlighted with a unique color.

<p>The register code is completely independent of the engine code, knows
nothing about accounting or any of the other GnuCash subsystems. It
can be used in independent projects that have nothing to do with
accounting.

<h2>Reports</h2>

<p>The <dfn>Reports</dfn> module (<code>src/scm/report.scm</code>,
<code>src/scm/reports</code>) is a scheme (guile) based system to create
balance sheets, profit &amp; loss statements, etc. by using the engine
API to fetch and display data formatted in HTML.

<p>This module is being redesigned to use the Query API to fetch the engine
information instead of using the raw engine interface. The new reporting
design will use Queries to extract the data and assemble it into a
view-independent format. This data will then be converted to HTML
reports and/or graphs such as bar and pie charts.

<h2>Graphs</h2>

<p>The <dfn>Graphs</dfn> module will be a future module implementing GUI graphs
such as bar and pie charts. These graphs will be interactive in that the
user can, for example, move pie wedges, and 'live' in that the user will
be able to click on graph subsections to see a detail graph or report of
that particular subsection.

<p>This module will be implemented using the GUPPI library being developed
by Jon Trowbridge (&lt;<code>http://www.gnome.org/guppi</code>&gt;).

<h2>Price Quotes</h2>

<p>The <dfn>Price Quotes</dfn> module (<code>src/quotes</code>) is a Perl system to
fetch stock price data off the Internet and insert it into the GnuCash
Engine. This module requires the functionality of the Finance::Quote
module available at SourceForge. The Finance::Quote module can fetch
price quotes from many different sources including Yahoo, Yahoo Europe,
and some international exchanges.

<p>The Finance::Quote module also supports fetching currency exchange
rates. GnuCash will be extended to allow the fetching and use of
currency exchange rates.

<h2>User Preferences</h2>

<p>The <dfn>User Preferences</dfn> module (<code>src/scm/options.scm</code>,
<code>src/scm/prefs.scm</code>) provides an infrastructure for defining both
user-configurable and internal preferences. Preferences are defined in
scheme using several predefined preference types such as boolean,
string, date, etc. Preferences are 'implemented' by providing a GUI
which allows the user to see and change preference values. An API
is provided to query preference values and to register callbacks
which will be invoked when preferences change.

<p>Preference values which are different from the default values
are stored as scheme forms in a user-specific preferences file
(<code>~/.gnucash/config.auto</code>). This file is automatically
loaded upon startup.

<h2>QIF Import</h2>

<p>The <dfn>QIF Import</dfn> module (<code>src/scm/qif-import</code>) provides
functionality for importing QIF (Quicken Interchange Format) data
into GnuCash.

<h2>GnuCash</h2>

<p>The GnuCash module (<code>src/gnome</code>, <code>src/register/gnome</code> and
<code>src/*.[ch]</code>) is the main GUI application. It consists of a
collection of miscellaneous GUI code to glue together all of the pieces
above into a coherent, point-and-click whole. It is meant to be easy to
use and intuitive to the novice user without sacrificing the power and
flexibility that a professional might expect. When people say that
GnuCash is trying to be a "Quicken or MS Money look/work/act-alike",
this is the piece that they are referring to. It really is meant to
be a personal-finance manager with enough power for the power user
and the ease of use for the beginner.

<p>Currently, the Gnome interface is the only operational interface. There
is an obsolete Motif interface which is not maintained. The Qt code
won't compile, and most/all functions are missing.


<?php include("include/table_bottom.inc"); ?>
      
<! END CONTENT>


<?php include("include/footer.inc"); ?> 