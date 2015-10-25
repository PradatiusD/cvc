# Center for Visual Communication

The Center for Visual Communication organizes and presents art exhibitions at its Miami gallery located in Wynwood and at public venues in South Florida including The Arsht Center for Performing Arts, Florida International University, Miami Science Museum and Miami Dade College. CVC has presented major retrospective exhibitions of noted international artists including Robert Rauschenberg and Edward Weston as well as Florida Masters Darby Bannard,  Clyde Butcher and Bunny Yeager.

```html
<h3 class="subheader">The Center for Visual Communication is located in the heart of Miami's Wynwood Art District, next to the Margulies Collection</h2>

<address>
<h4>Center for Visual Communication</h4>
541 NW 27th Street<br>
Miami FL 33127<br>
<a href="tel:3055711415"></a>305-571-1415
</address>

<h4>Hours</h4>
<table>
  <tbody>
    <tr>
      <td><strong>Tuesday-Friday</strong></td>
      <td>10am – 5pm</td>
    </tr>
    <tr>
      <td><strong>Saturday</strong></td>
      <td>12pm – 5pm</td>
    </tr>
  </tbody>
</table>
```

## Helpful links for Dev

* [Types PHP API](http://wp-types.com/documentation/user-guides/displaying-wordpress-custom-fields/)
* Foundation 5 Documentation


# Features to be added
- Current and Past Exhibitions taxonomy added
- Add plugin for css
- News section

# Topic of conversation
instagram
http://note.io/1zOexA2


# Sass conflict issue

See [this compilation issue](http://foundation.zurb.com/forum/posts/18856-sass-342-compilation-problem)

See 

```bash
gem uninstall sass
gem install sass -v 3.2.19
```


   function has_post_state_taxonomy () {
    $archive_name = get_queried_object();
    $archive_name = $archive_name->rewrite['slug'];

    if ($archive_name == 'exhibition' || $archive_name == 'event' || $archive_name == 'program') {
      return true;
    }
  }
