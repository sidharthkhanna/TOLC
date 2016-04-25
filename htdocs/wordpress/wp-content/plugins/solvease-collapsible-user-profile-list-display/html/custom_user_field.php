<h3>Social Media URl</h3>
                    
<table class="form-table">
      <tr>
            <th><label for="twitter">Twitter</label></th>
             <td>
                <input type="text" name="twitter_name" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter_name', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Please enter your Twitter User Name </span>
            </td>
            <td>
                <input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Please enter your Twitter link </span>
            </td>
            
       </tr>
       <tr>
            <th><label for="facebook">Facebook</label></th>
             <td>
                <input type="text" name="facebook_name" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook_name', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Please enter your facebook User Name</span>
            </td>
            <td>
                <input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Please enter your facebook link </span>
            </td>
       </tr>
       
       <tr>
            <th><label for="linkIn">linkedIn</label></th>
            <td>
                <input type="text" name="linkIn_name" id="linked_in" value="<?php echo esc_attr( get_the_author_meta( 'linkIn_name', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Please enter your linkedIn User Name </span>
            </td>
            <td>
                <input type="text" name="linkIn" id="linked_in" value="<?php echo esc_attr( get_the_author_meta( 'linkIn', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Please enter your linked_in link </span>
            </td>
       </tr>
   </table>

 <h3>Additional Information</h3>
   <table class="form-table">
       <tr>
            <th><label for="occupation">Occupation</label></th>
            <td>
                <input type="text" name="occupation" id="linked_in" value="<?php echo esc_attr( get_the_author_meta( 'occupation', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Please enter your Occupation </span>
            </td>
       </tr>
       <tr>
            <th><label for="company_name">Company Name</label></th>
            <td>
                <input type="text" name="company_name" id="linked_in" value="<?php echo esc_attr( get_the_author_meta( 'company_name', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Please enter your Occupation </span>
            </td>
       </tr>
       <tr>
            <th><label for="education">Education</label></th>
            <td>
                <input type="text" name="education" id="linked_in" value="<?php echo esc_attr( get_the_author_meta( 'education', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Please enter your Occupation </span>
            </td>
       </tr>
  </table>