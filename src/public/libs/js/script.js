$("#addBtn").click(function () {
  // Replicate the logic of the refresh button click to open the add modal for the table that is currently on display
  if ($("#personnelBtn").hasClass("active")) {
    createPersonnelModal();
  } else {
    if ($("#departmentsBtn").hasClass("active")) {
      createDepartmentModal();
    } else {
      createLocationModal();
    }
  }
});

$("#refreshBtn").click(function () {
  if ($("#personnelBtn").hasClass("active")) {
    getAllPersonnel();
  } else {
    if ($("#departmentsBtn").hasClass("active")) {
      getAllDepartments();
    } else {
      getAllLocations();
    }
  }
});

// ---------------------------->  Handle CRUD Personnel Functions  <-----------------------------------

// Create Personnel Modal
function createPersonnelModal() {
  // Open the modal
  $("#addPersonnelModal").modal("show");
  // Clear the form fields
  $("#addPersonnelModal :input").each(function () {
    $(this).val("");
  });
}

// Create Personnel Modal
$("#addPersonnelModal").on("show.bs.modal", function (event) {
  // Perform any actions you need when the modal is about to be shown
  $.ajax({
    url: "libs/php/department/getAllDepartments.php",
    type: "GET",
    dataType: "json",
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        result.data.forEach((department) => {
          $("#addPersonnelDepartment").append(
            `<option value="${department.id}">${department.name}</option>`
          );
        });
      } else {
        // Display an error message
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});

// Create Personnel Form
$("#addPersonnelForm").on("submit", function (e) {
  e.preventDefault();
  const firstName = $("#addPersonnelFirstName").val();
  const lastName = $("#addPersonnelLastName").val();
  const email = $("#addPersonnelEmailAddress").val();
  const departmentID = $("#addPersonnelDepartment").val();

  let jobTitle;
  if ($("#addPersonnelJobTitle").val() == "") {
    jobTitle = "N/A";
  } else {
    jobTitle = $("#addPersonnelJobTitle").val();
  }

  $.ajax({
    url: "libs/php/personnel/createPersonnel.php",
    type: "POST",
    dataType: "json",
    data: {
      firstName: firstName,
      lastName: lastName,
      jobTitle: jobTitle,
      email: email,
      departmentID: departmentID,
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        // Refresh personnel table
        $("#addPersonnelModal").modal("hide");
        showToast("Personnel added successfully", 5000, "green");
        getAllPersonnel();
      } else {
        // Display an error message
        showToast("Error adding personnel", 5000, "red");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});

// Read all personnel
function getAllPersonnel() {
  $.ajax({
    url: "libs/php/personnel/getAll.php",
    type: "POST",
    dataType: "json",
    data: {
      txt: $("#searchInp").val().toLowerCase(),
    },
    success: function (result) {
      $("#personnelTable").empty();
      var resultCode = result.status.code;
      if (resultCode == 200) {
        if (result.data.length == 0) {
          $("#personnelTable").append(
            `<tr>
             <td class="text-center" colspan="6">
              <h5>No Personnel found</h5>
             </td></tr>`
          );
        }
        // loop through the result and display the data in the table
        result.data.forEach((personnel) => {
          $("#personnelTable").append(
            `<tr>
            <td class="align-middle text-nowrap">${personnel.lastName}, ${personnel.firstName}</td>
            <td class="align-middle text-nowrap d-none d-md-table-cell">
              ${personnel.department}
            </td>
            <td class="align-middle text-nowrap d-none d-md-table-cell">
               ${personnel.location}
            </td>
            <td class="align-middle text-nowrap d-none d-md-table-cell">
               ${personnel.email}
            </td>
             <td class="align-middle text-nowrap d-none d-md-table-cell">
               ${personnel.jobTitle}
            </td>
            <td class="text-end text-nowrap">
              <button type="button" 
              class="btn btn-primary btn-sm"
              data-bs-toggle="modal" 
              data-bs-target="#editPersonnelModal"
              data-id=${personnel.id}>
              <i class="fa-solid fa-pencil fa-fw"></i>
              </button>
              <button type="button" 
              class="btn btn-primary btn-sm" 
              data-bs-toggle="modal" 
              data-bs-target="#deletePersonnelModal"
              data-id=${personnel.id}>
              <i class="fa-solid fa-trash fa-fw"></i>
              </button>
            </td>
          </tr>`
          );
        });
      } else {
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
}
// Call function to refresh personnel table
getAllPersonnel();

// Button click event to refresh personnel table
$("#personnelBtn").click(function () {
  $("#searchInp").val(""); // clear search input
  getAllPersonnel();
});

// Update Personnel Modal - Get personnel by ID
$("#editPersonnelModal").on("show.bs.modal", function (e) {
  $.ajax({
    url: "libs/php/personnel/getPersonnelByID.php",
    type: "POST",
    dataType: "json",
    data: {
      id: $(e.relatedTarget).attr("data-id"),
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        $("#editPersonnelEmployeeID").val(result.data.personnel[0].id);
        $("#editPersonnelFirstName").val(result.data.personnel[0].firstName);
        $("#editPersonnelLastName").val(result.data.personnel[0].lastName);
        $("#editPersonnelJobTitle").val(result.data.personnel[0].jobTitle);
        $("#editPersonnelEmailAddress").val(result.data.personnel[0].email);
        $("#editPersonnelDepartment").html("");

        result.data.department.forEach((department) => {
          $("#editPersonnelDepartment").append(
            `<option value="${department.id}">${department.name}</option>`
          );
        });
        $("#editPersonnelDepartment").val(
          result.data.personnel[0].departmentID
        );
      } else {
        $("#editPersonnelModal .modal-title").replaceWith(
          "Error retrieving data"
        );
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});

// Update Personnel Form
$("#editPersonnelForm").on("submit", function (e) {
  e.preventDefault();
  const id = $("#editPersonnelEmployeeID").val();
  const firstName = $("#editPersonnelFirstName").val();
  const lastName = $("#editPersonnelLastName").val();
  const jobTitle = $("#editPersonnelJobTitle").val();
  const email = $("#editPersonnelEmailAddress").val();
  const departmentID = $("#editPersonnelDepartment").val();

  $.ajax({
    url: "libs/php/personnel/updatePersonnelByID.php",
    type: "POST",
    dataType: "json",
    data: {
      id: id,
      firstName: firstName,
      lastName: lastName,
      jobTitle: jobTitle,
      email: email,
      departmentID: departmentID,
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        // Refresh personnel table
        $("#editPersonnelModal").modal("hide");
        showToast("Personnel updated successfully", 5000, "green");
        getAllPersonnel();
      } else {
        // Display an error message
        showToast("Error updating personnel", 5000, "red");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});

// Delete Personnel Modal - Get personnel by ID
$("#deletePersonnelModal").on("show.bs.modal", function (e) {
  $.ajax({
    url: "libs/php/personnel/getPersonnelByID.php",
    type: "POST",
    dataType: "json",
    data: {
      id: $(e.relatedTarget).attr("data-id"),
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        let personnel =
          result.data.personnel[0].lastName +
          ", " +
          result.data.personnel[0].firstName;
        $("#employeeName").html(personnel);
        $("#deletePersonnelEmployeeID").val(result.data.personnel[0].id);
      } else {
        $("#editPersonnelModal .modal-title").replaceWith(
          "Error retrieving data"
        );
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});
// Delete Personnel Form
$("#deletePersonnelForm").on("submit", function (e) {
  e.preventDefault();
  const id = $("#deletePersonnelEmployeeID").val();
  $.ajax({
    url: "libs/php/personnel/deletePersonnelByID.php",
    type: "POST",
    dataType: "json",
    data: {
      id: id,
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        // Refresh personnel table
        $("#deletePersonnelModal").modal("hide");
        showToast("Personnel deleted successfully", 5000, "green");
        getAllPersonnel();
      } else {
        // Display an error message
        showToast("Error deleting personnel", 5000, "red");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});

// ----------------------------->  Handle CRUD Department Functions  <------------------------------

// Create Personnel Modal
function createDepartmentModal() {
  // Open the modal
  $("#addDepartmentModal").modal("show");
  // Clear the form fields
  $("#addDepartmentModal :input").each(function () {
    $(this).val("");
  });
}
$("#addDepartmentModal").on("show.bs.modal", function (event) {
  $.ajax({
    url: "libs/php/location/getAllLocations.php",
    type: "GET",
    dataType: "json",
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        result.data.forEach((location) => {
          $("#addDepartmentLocation").append(
            `<option value="${location.id}">${location.name}</option>`
          );
        });
      } else {
        // Display an error message
      }
    },
  });
});

// Create Department Form
$("#addDepartmentForm").on("submit", function (e) {
  e.preventDefault();
  const name = $("#addDepartmentName").val();
  const locationID = $("#addDepartmentLocation").val();
  $.ajax({
    url: "libs/php/department/createDepartment.php",
    type: "POST",
    dataType: "json",
    data: {
      name: name,
      locationID: locationID,
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        // Refresh department table
        $("#addDepartmentModal").modal("hide");
        showToast("Department added successfully", 5000, "green");
        getAllDepartments();
      } else {
        // Display an error message
        showToast("Error adding department", 5000, "red");
      }
    },
  });
});

function getAllDepartments() {
  $.ajax({
    url: "libs/php/department/getAllDepartments.php",
    type: "POST",
    dataType: "json",
    data: {
      txt: $("#searchInp").val().toLowerCase(),
    },
    success: function (result) {
      $("#departmentTable").empty();
      if (result.data.length == 0) {
        $("#departmentTable").append(
          `<tr>
             <td class="text-center" colspan="6">
              <h5>No Department found</h5>
             </td></tr>`
        );
      }
      var resultCode = result.status.code;
      if (resultCode == 200) {
        // loop through the result and display the data in the table
        result.data.forEach((department) => {
          $("#departmentTable").append(
            `<tr>
            <td class="align-middle text-nowrap">${department.name}</td>
            <td class="align-middle text-nowrap d-none d-md-table-cell">
              ${department.location}
            </td>
            <td class="align-middle text-end text-nowrap">
              <button type="button"
                 class="btn btn-primary btn-sm"
                 data-bs-toggle="modal"
                 data-bs-target="#editDepartmentModal"
                 data-id=${department.id}>
                   <i class="fa-solid fa-pencil fa-fw"></i>
              </button>
              <button type="button"
                class="btn btn-primary btn-sm deleteDepartmentBtn"
                 data-bs-toggle="modal"
                 data-bs-target="#deleteDepartmentModal"
                 data-id=${department.id}>
                 <i class="fa-solid fa-trash fa-fw"></i>
              </button>
            </td>
          </tr>`
          );
        });
      } else {
        // Display an error message
      }
    },
  });
}

$("#departmentsBtn").click(function () {
  $("#searchInp").val(""); // clear search input
  getAllDepartments();
});

// Update Department Modal - Get Department by ID
$("#editDepartmentModal").on("show.bs.modal", function (e) {
  $.ajax({
    url: "libs/php/department/getDepartmentByID.php",
    type: "POST",
    dataType: "json",
    data: {
      id: $(e.relatedTarget).attr("data-id"),
    },
    success: function (result) {
      console.log(result);
      var resultCode = result.status.code;
      if (resultCode == 200) {
        $("#editDepartmentID").val(result.data.department[0].id);
        $("#editDepartmentName").val(result.data.department[0].name);
        $("#editDepartmentLocation").html("");
        result.data.location.forEach((location) => {
          $("#editDepartmentLocation").append(
            `<option value="${location.id}">${location.name}</option>`
          );
        });
        $("#editDepartmentLocation").val(result.data.department[0].locationID);
      } else {
        $("#editDepartmentModal .modal-title").replaceWith(
          "Error retrieving data"
        );
      }
    },
  });
});

// Update Department Form
$("#editDepartmentForm").on("submit", function (e) {
  e.preventDefault();
  const id = $("#editDepartmentID").val();
  const name = $("#editDepartmentName").val();
  const locationID = $("#editDepartmentLocation").val();

  $.ajax({
    url: "libs/php/department/updateDepartmentByID.php",
    type: "POST",
    dataType: "json",
    data: {
      id: id,
      name: name,
      locationID: locationID,
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        // Refresh department table
        $("#editDepartmentModal").modal("hide");
        showToast("Department updated successfully", 5000, "green");
        getAllDepartments();
      } else {
        // Display an error message
        showToast("Error updating department", 5000, "red");
      }
    },
  });
});

// Delete Department Modal - Get Department by ID
$("#deleteDepartmentModal").on("show.bs.modal", function (e) {
  $.ajax({
    url: "libs/php/department/getPersonnelCount.php",
    type: "POST",
    dataType: "json",
    data: {
      id: $(e.relatedTarget).attr("data-id"),
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        $("#deleteDepartmentID").val(result.data[0].id);
        if (result.data[0].personnel_count > 0) {
          $("#departmentName").html(result.data[0].name);
          $("#employeeCount").html(result.data[0].personnel_count);
          $("#filledDepartment").show();
          $("#filledDeptMessage").show();
          $("#emptyDepartment").hide();
          $("#emptyDeptMessage").hide();
        } else {
          $("#emptyDepartmentName").html(result.data[0].name);
          $("#emptyDeptMessage").show();
          $("#emptyDepartment").show();
          $("#filledDepartment").hide();
          $("#filledDeptMessage").hide();
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});

// Delete Department Form
$("#deleteDepartmentForm").on("submit", function (e) {
  e.preventDefault();
  const id = $("#deleteDepartmentID").val();
  $.ajax({
    url: "libs/php/department/deleteDepartmentByID.php",
    type: "POST",
    dataType: "json",
    data: {
      id: id,
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        // Refresh personnel table
        $("#deleteDepartmentModal").modal("hide");
        showToast("Personnel deleted successfully", 5000, "green");
        getAllDepartments();
      } else {
        // Display an error message
        showToast("Error deleting personnel", 5000, "red");
      }
    },
  });
});

// ----------------------------->  Handle CRUD Location Functions  <------------------------------

// Create Location Modal
function createLocationModal() {
  // Open the modal
  $("#addLocationModal").modal("show");
  // Clear the form fields
  $("#addLocationModal :input").each(function () {
    $(this).val("");
  });
}
// Create Location Form
$("#addLocationForm").on("submit", function (e) {
  e.preventDefault();
  const name = $("#addLocationName").val();
  $.ajax({
    url: "libs/php/location/createLocation.php",
    type: "POST",
    dataType: "json",
    data: {
      name: name,
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        // Refresh location table
        $("#addLocationModal").modal("hide");
        showToast("Location added successfully", 5000, "green");
        getAllLocations();
      } else {
        // Display an error message
        showToast("Error adding location", 5000, "red");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});

// Button click event to refresh location table
$("#locationsBtn").click(function () {
  $("#searchInp").val(""); // clear search input
  getAllLocations();
});

// Read all locations
function getAllLocations() {
  $.ajax({
    url: "libs/php/location/getAllLocations.php",
    type: "POST",
    dataType: "json",
    data: {
      txt: $("#searchInp").val().toLowerCase(),
    },
    success: function (result) {
      $("#locationTable").empty();
      var resultCode = result.status.code;
      if (resultCode == 200) {
        // loop through the result and display the data in the table
        result.data.forEach((location) => {
          $("#locationTable").append(
            `<tr>
            <td class="align-middle text-nowrap">${location.name}</td>
            <td class="align-middle text-end text-nowrap">
              <button type="button" 
                 class="btn btn-primary btn-sm"  
                 data-bs-toggle="modal"
                 data-bs-target="#editLocationModal"
                 data-id=${location.id}
              > 
              <i class="fa-solid fa-pencil fa-fw"></i>
              </button>
              <button type="button"  
                 class="btn btn-primary btn-sm"  
                 data-bs-toggle="modal"
                 data-bs-target="#deleteLocationModal"
                 data-id=${location.id}
               > 
                <i class="fa-solid fa-trash fa-fw"></i>
              </button>
            </td>
          </tr>`
          );
        });
      } else {
        // Display an error message
        showToast("Error deleting personnel", 5000, "red");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
}

// Update Location Modal - Get Location by ID
$("#editLocationModal").on("show.bs.modal", function (e) {
  $.ajax({
    url: "libs/php/location/getLocationByID.php",
    type: "POST",
    dataType: "json",
    data: {
      id: $(e.relatedTarget).attr("data-id"),
    },
    success: function (result) {
      const resultCode = result.status.code;
      if (resultCode == 200) {
        $("#editLocationID").val(result.data[0].id);
        $("#editLocationName").val(result.data[0].name);
      } else {
        // Display an error message
        showToast("Error deleting personnel", 5000, "red");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});

// Update Location Form
$("#editLocationForm").on("submit", function (e) {
  e.preventDefault();
  const id = $("#editLocationID").val();
  const name = $("#editLocationName").val();
  $.ajax({
    url: "libs/php/location/updateLocationByID.php",
    type: "POST",
    dataType: "json",
    data: {
      id: id,
      name: name,
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        // Refresh location table
        $("#editLocationModal").modal("hide");
        showToast("Location updated successfully", 5000, "green");
        getAllLocations();
      } else {
        // Display an error message
        showToast("Error updating location", 5000, "red");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});

// Delete Department Modal - Get Department by ID
$("#deleteLocationModal").on("show.bs.modal", function (e) {
  $.ajax({
    url: "libs/php/location/getLocationCount.php",
    type: "POST",
    dataType: "json",
    data: {
      id: $(e.relatedTarget).attr("data-id"),
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        $("#deleteLocationID").val(result.data[0].id);
        if (result.data[0].location_count > 0) {
          $("#locationName").html(result.data[0].name);
          $("#locationCount").html(result.data[0].location_count);
          $("#filledLocation").show();
          $("#filledLocationMessage").show();
          $("#emptyLocation").hide();
          $("#emptyLocationMessage").hide();
        } else {
          $("#emptyLocationName").html(result.data[0].name);
          $("#emptyLocationMessage").show();
          $("#emptyLocation").show();
          $("#filledLocation").hide();
          $("#filledLocationMessage").hide();
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});

// Delete Location Form
$("#deleteLocationForm").on("submit", function (e) {
  e.preventDefault();
  $.ajax({
    url: "libs/php/location/deleteLocationByID.php",
    type: "POST",
    dataType: "json",
    data: {
      id: $("#deleteLocationID").val(),
    },
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        // Refresh location table
        $("#deleteLocationModal").modal("hide");
        showToast("Location deleted successfully", 5000, "green");
        getAllLocations();
      } else {
        // Display an error message
        showToast("Error deleting location", 5000, "red");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});

// -------------------------->  Search and Filter Functions  <-------------------------------
$("#searchInp").on("keyup", function () {
  // your code
  if ($("#personnelBtn").hasClass("active")) {
    getAllPersonnel();
  } else {
    if ($("#departmentsBtn").hasClass("active")) {
      getAllDepartments();
    } else {
      getAllLocations();
    }
  }
});

$("#filterBtn").click(function () {
  $("#filterLocation").prop("disabled", false);
  $("#filterDepartment").prop("disabled", false);
  getAllPersonnel();
  $("#filterModal").modal("show");
});

$("#filterModal").on("show.bs.modal", function (e) {
  $.ajax({
    url: "libs/php/department/getAllDepartments.php",
    type: "GET",
    dataType: "json",
    success: function (result) {
      var resultCode = result.status.code;
      if (resultCode == 200) {
        result.data.forEach((department) => {
          $("#filterDepartment").append(
            `<option value="${department.name}">${department.name}</option>`
          );
        });
        $.ajax({
          url: "libs/php/location/getAllLocations.php",
          type: "GET",
          dataType: "json",
          success: function (result) {
            var resultCode = result.status.code;
            if (resultCode == 200) {
              result.data.forEach((location) => {
                $("#filterLocation").append(
                  `<option value="${location.name}">${location.name}</option>`
                );
              });
            } else {
              showToast("Error retrieving data", 5000, "red");
              // Display an error message
            }
          },
        });
      } else {
        showToast("Error retrieving data", 5000, "red");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      showToast("Error retrieving data", 5000, "red");
    },
  });
});

$("#filterPersonnelForm").on("change", function (e) {
  const departmentID = $("#filterDepartment").val();
  const locationID = $("#filterLocation").val();

  if (departmentID !== "") {
    $("#filterLocation").prop("disabled", true);
  } else if (locationID !== "") {
    $("#filterDepartment").prop("disabled", true);
  } else {
    $("#filterLocation").prop("disabled", false);
    $("#filterDepartment").prop("disabled", false);
  }
});

$("#filterPersonnelForm").on("submit", function (e) {
  e.preventDefault();
  const departmentID = $("#filterDepartment").val();
  const locationID = $("#filterLocation").val();

  var tbody = $("#personnelTable");
  var rows = tbody.children("tr").toArray();

  if (locationID == "default" && departmentID == "default") {
    showToast("Please select a filter option Or Cancel", 5000, "red");
    return;
  } else if (departmentID !== "default") {
    tbody.empty();
    const filterDept = rows.filter((row) => {
      return row.innerText.includes(departmentID);
    });
    tbody.append(filterDept);
  } else if (locationID !== "default") {
    tbody.empty();
    const filterLoc = rows.filter((row) => {
      return row.innerText.includes(locationID);
    });
    tbody.append(filterLoc);
  }

  $("#filterModal").modal("hide");
});

// --------------------------> Toast <------------------------------- //

function showToast(message, duration, color) {
  Toastify({
    text: message,
    duration: duration,
    newWindow: true,
    close: true,
    gravity: "top", // `top` or `bottom`
    position: "center", // `left`, `center` or `right`
    stopOnFocus: true, // Prevents dismissing of toast on hover
    style: {
      background: `${color}`,
    },
    onClick: function () {}, // Callback after click
  }).showToast();
}
