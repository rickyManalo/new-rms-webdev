<?php
    include dirname(__FILE__, 4) . '/helpers/db.php';
    include dirname(__FILE__, 4) . '/components/header/header.php';
    include dirname(__FILE__, 4) . '/components/public-user/templates/user-navbar.php'; 
    // Check if the 'edit' form has been submitted
    // This condition is used to determine if the code block should be executed when the 'edit' form is submitted
    if (isset($_POST['edit'])) {
    // The code inside this block will be executed only if the 'edit' form has been submitted
?>
<link rel="stylesheet" href="../../../css/index.css">
<link rel="stylesheet" href="../ip-assets/new/new-ip-asset.css">

<body>
    <!-- <?php
        include '../../../includes/admin/templates/navbar.php';
    ?> -->
    <main>
        <div class="header">
            <h1 class="title">Edit IP Asset</h1>
        </div>
        <section>
            <div class="container">
            <?php
                $ipaID = $_POST['row_id'];
                $fetchdata = pg_query($conn, "SELECT * FROM table_ipassets WHERE registration_number = '$ipaID'"); //Fetch data from the 'table_ipassets' table based on the registration number
                while($row = pg_fetch_assoc($fetchdata)){  //Iterate through the fetched data
            ?>
            <form name="form-ipa" id="form-ipa" action="functionalities/button_functions/ipa-edit.php" method="POST" enctype="multipart/form-data" onsubmit="return checkDuplicateAuthors()">
                    <div class="sub-container">
                        <div class="title">
                            <h3>Document Details</h3>
                            <hr>
                        </div>
                        <div class="form-col">
                            <div class="form-container">
                                <div class="form-control">
                                    <label for="ip-title" class="ip-label">Work Title <span style="color: red;">*</span></label>
                                    <input type="text" placeholder="Work Title..." id="ip-title" name="title_of_work" required value="<?=$row['title_of_work']?>">
                                </div>
                                <div class="form-control">
                                    <label class="ip-label" for="ip-type">Type of Document</label>
                                    <select  name="type_of_ipa" id="ip-type">
                                        <option value="<?=$row['type_of_document']?>" hidden><?=$row['type_of_document']?></option>
                                        <option value="Original Article">Original Article</option>
                                        <option value="Review">Review</option>
                                        <option value="Proceedings">Proceedings</option>
                                        <option value="Communication">Communication</option>
                                        <option value="International">International</option>
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="ipa-label" for="class_of_work">Class of Work</label>
                                    <select class="ipa-input-field" id="class_of_work" name="class_of_work">
                                        <option value="<?=$row['class_of_work']?>" hidden><?=$row['class_of_work']?></option>
                                        <option value="A">Class A</option>
                                        <option value="G">Class G</option>
                                        <option value="O">Class O</option>
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="ipa-label" for="ipa-campus">Campus</label>
                                    <select name="campus" class="ipa-input-field">
                                        <option value="<?=$row['campus']?>" hidden><?=$row['campus']?></option>
                                        <option value="Alangilan">Alangilan</option>
                                        <option value="Balayan">Balayan</option>
                                        <option value="Lemery">Lemery</option>
                                        <option value="Lipa">Lipa</option>
                                        <option value="Lobo">Lobo</option>
                                        <option value="Mabini">Mabini</option>
                                        <option value="Malvar">Malvar-JPCPC</option>
                                        <option value="Nasugbu">Nasugbu-Arasof</option>
                                        <option value="Pablo Borbon (Main I)">Pablo Borbon</option>
                                        <option value="Padre Garcia">Padre Garcia</option>
                                        <option value="Rosario">Rosario</option>
                                        <option value="San Juan">San Juan</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-container">
                                <div class="form-control">
                                    <label class="ipa-label" for="program">Program</label>
                                    <select name="program" class="ipa-input-field">
                                            <option value="<?=$row['program']?>" hidden><?=$row['program']?></option>
                                            <option value="Accountancy, Business, and International Hospitality">Accountancy, Business, and International Hospitality</option>
                                            <option value="Agriculture and Forestry">Agriculture and Forestry</option>
                                            <option value="Arts and Sciences">Arts and Sciences</option>
                                            <option value="Basic Education">Basic Education</option>
                                            <option value="College of Medicine">College of Medicine</option>
                                            <option value="Engineering, Architecture and Fine Arts">Engineering, Architecture and Fine Arts</option>
                                            <option value="ETEEAP">ETEEAP</option>
                                            <option value="Informatics and Computing Sciences">Informatics and Computing Sciences</option>
                                            <option value="Industrial Technology">Industrial Technology</option>
                                            <option value="Law">Law</option>
                                            <option value="Nursing, Nutrition and Dietetics">Nursing, Nutrition and Dietetics</option>
                                            <option value="Teacher Education">Teacher Education</option>
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="ipa-label" for="college">College</label>
                                    <input type="text" class="ipa-input-field" id="college" name="college" placeholder="College..." value="<?=$row['college']?>">
                                </div>
                                <div class="form-control">
                                    <label class="ipa-label" for="date-of-creation">Date of Creation</label>
                                    <input type="date" max="<?= date('Y-m-d'); ?>" id="date-of-creation" name="date_of_creation" value="<?=$row['date_of_creation']?>">
                                </div>
                                <div class="form-control">
                                    <label class="ipa-label" for="hyperlink">Hyperlink</label>
                                    <input type="text" class="ipa-input-field" id="hyperlink" name="hyperlink" placeholder="URL..." value="<?=$row['hyperlink']?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sub-container">
                        <div class="title">
                            <h3>Author Details</h3>
                            <hr>
                        </div>
                        <div class="form-col">
                            <div class="author-table-container">
                            <table id="author-tbl">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="author-tbl-body">
                                <?php                                        
                                $author_list = $row["authors"];
                                $authors = explode(",", $author_list);
                                foreach ($authors as $author) { //Iterate through each author in the list
                                    $authorData = pg_query($conn, "SELECT author_id, author_name FROM table_authors WHERE author_id = '$author'");                                            
                                    while($author_list_row = pg_fetch_assoc($authorData)){ //Process the data for each author                                    
                                        echo '
                                        <tr>
                                        <td class="ipa-author-field">
                                        <input list="authors" name="author_name[]"
                                        style="
                                        width: 100%;
                                        height: 50px;
                                        padding: 10px 36px 10px 16px;
                                        border-radius: 5px;
                                        border: 1px solid var(--dark-grey);"                                                                                        
                                        placeholder="Author Name..."
                                        value="' . $author_list_row['author_name'] . '">';
                                        echo '<datalist id="authors">';
                                        $query = "SELECT author_id, author_name FROM table_authors ORDER BY author_name"; // Fetch the list of authors from the 'table_authors' table
                                        $params = array();
                                        $result = pg_query_params($conn, $query, $params);
                                        while ($author_row = pg_fetch_assoc($result)) { // Iterate through each author in the result set and display them as options
                                            echo '<option value="' . $author_row['author_name'] . '">' . $author_row['author_id'] . '</option>';
                                        }
                                        echo '</datalist>';
                                        echo'
                                        </td>                                                
                                        <td class="ipa-author-field" style="text-align:center;"><button name="remove" style="height: 50px; width:3.7rem; border-radius: 5px; border: none; padding: 0 20px; background: var(--primary); color: var(--light); font-size: 25px; font-weight: 600; cursor: pointer; letter-spacing: 1px; font-weight: 600;"id="remove"><i class="fas fa-xmark fa-xs"></i></button></td>
                                        </tr>';
                                    }
                                }                                            
                                ?>
                            </tbody>
                                            <td style="text-align: center;" colspan="2">
                                                <button type="button" class="add-row-btn" style="height: 50px; width: 10%;">+</button>
                                            </td>
                                            <div id="error-msg" style="display: none; color: red;">Duplicate author names are not allowed!</div>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="sub-container">
                        <div class="title">
                            <h3>Registration Details</h3>
                            <hr>
                        </div>
                        <div class="form-col">
                            <div class="reg-form-container">
                                <label class="reg-titles">Registered? <span style="color: red;">*</span></label>
                                <div class="form-control">
                                    <div class="choices">
                                        <input type="radio" name="registerInfo" id="registered" value="registered">
                                        <label for="registered" class="reg-choices">Yes</label>
                                    </div>
                                    <div class="choices">
                                        <input type="radio" name="registerInfo" id="not-registered" value="not-registered" checked="checked">
                                        <label for="not-registered" class="reg-choices">No</label>
                                    </div>
                                </div>
                            </div>
                            <h4 class="if-funded">If Registered : </h4>
                            <div class="reg-form-container2">
                                <div class="form-control">
                                    <label class="ip-label" for="reg-num" id="reg_num">Registration Number: <span style="color: red;">*</span></label>
                                    <input type="text" name="registration_number" id="reg_num" placeholder="Registration Number..." required  value="<?=$row['registration_number']?>" readonly>
                                </div>
                                <div class="form-control">
                                    <label class="ip-label" for="reg-date">Date of Registration</label>
                                    <input type="date" max="<?= date('Y-m-d'); ?>" name="date_registered" id="reg-date" value="<?=$row['date_registered']?>">
                                </div>
                                <div class="form-control">
                                    <label class="ip-label" for="ip-certificate">Upload Certificate: <span id="file-error" style="display: none; color: red;">Only PNG, JPG, JPEG, and PDF file types are allowed!</span></label>
                                    <input type="file" name="ip-certificate" id="ip-certificate-input" onchange="checkFileType(this)" accept=".png, .jpg, .jpeg, .pdf">

                                        <?php if (!empty($row['certificate'])): $dir="functionalities/button_functions/"?> <!-- Check if a certificate file is not empty -->
                                            <div>File uploaded: <a href="<?=$dir.$row['certificate']?>" target="_blank"><?=basename($row['certificate'])?></a></div> <!--Display the file details with a link to open it in a new tab-->
                                        <?php else: ?>
                                            <div>File uploaded: None</div> <!--No certificate file is present, display a message indicating none-->
                                        <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                 <hr>
                <div class="form-footer">
                    <input type="submit" class="submit-btn" name="updateIPA" value="Submit">
                    <input type="hidden" name="updateIPA" value="true">
                    <input type="button" class="cancel-btn" value="Cancel">
                </div>
            </form>
            <?php
                }
                ?>
            </div>
        </section>
    </main>
<script src="new/new-ip-asset.js"></script>
</body>
<?php
    include 'new/new-ip-asset-js.php';
    }else{
        header("Location: ../ip-assets/ip-assets.php");
    }
?>