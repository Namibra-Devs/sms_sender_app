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
};
