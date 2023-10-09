const btns = document.querySelectorAll(".tab-btn");
const items = document.querySelectorAll(".blogpost-card");

for (i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", (e) => {
    e.preventDefault();

    const filterProduct = e.target.dataset.filter;

    items.forEach((item) => {
      if (filterProduct == "all") {
        item.style.display = "block";
      } else {
        if (item.classList.contains(filterProduct)) {
          item.style.display = "block";
        } else {
          item.style.display = "none";
        }
      }
    });
  });
}

// sweet alert js code follows
window.showAlert = async (icon, title) => {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-right",
    iconColor: "white",
    customClass: {
      popup: "colored-toast",
    },
    showConfirmButton: false,
    timer: 4500,
    timerProgressBar: true,
  });
  await Toast.fire({
    icon: icon,
    title: title,
  });

  // await Toast.fire({
  //   icon: "success",
  //   title: "Success",
  // });
  // await Toast.fire({
  //   icon: "error",
  //   title: "Error",
  // });
  // await Toast.fire({
  //   icon: "warning",
  //   title: "Warning",
  // });
};
//sweet alert ends

const menuBtns = document.querySelectorAll(".menu-btn");
menuBtns.forEach((menuBtn) => {
  menuBtn.addEventListener("click", (e) => {
    const popupMenus = e.currentTarget.parentElement.nextElementSibling;
    popupMenus.classList.toggle("pop-up-menu-show");
  });
});

// contentDoc =  document.querySelector(".content");
function formatDoc(cmd, value = null) {
  if (value) {
    document.execCommand(cmd, false, value);
  } else {
    document.execCommand(cmd);
  }
}

function addLink() {
  const url = prompt("Insert url here");
  formatDoc("createLink", url);
}
function addImage() {
  const url = prompt("Insert image here");
  formatDoc("createLink", url);
}

const dragger = document.getElementById("dragger");
const dragger_text = document.getElementById("dragger_text");
const browseBtn = document.getElementById("browseBtn");
const fileSelectorInput = document.getElementById("fileSelectorInput");

const browseFileHandle = () => {
  fileSelectorInput.click();
};
fileSelectorInput.addEventListener("change", function (e) {
  file = this.files[0];
  uploadfileHandler(file);
});
dragger.addEventListener("dragover", (e) => {
  e.preventDefault();
  dragger_text.textContent = "Release to Upload image";
});
dragger.addEventListener("dragleave", () => {
  dragger_text.textContent = "Drag and drop file";
});
dragger.addEventListener("drop", (e) => {
  e.preventDefault();
  file = e.dataTransfer.files[0];
  uploadfileHandler(file);
});
const uploadfileHandler = (file) => {
  const validFileExtension = [".jpeg", ".jpg", ".png"];
  if (validFileExtension.includes(file.type)) {
    const fileReader = new FileReader();
    fileReader.onload = () => {
      const fileURL = fileReader.result;
      const imageTag = `<img src=${fileURL} alt="" />`;
      dragger.innerHTML = imageTag;
    };

    fileReader.readAsDataURL(file);
  }
};
