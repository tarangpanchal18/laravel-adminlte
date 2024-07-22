<script>
@if (session('success'))
    Swal.fire({
        icon: "success",
        html: "{{ session('success') }}",
        toast: true,
        position: "top-end",
        timer: 3500,
        showConfirmButton: false,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
@endif

@if (session('error'))
    Swal.fire({
        icon: "success",
        html: "{{ session('error') }}",
        toast: true,
        position: "top-end",
        timer: 3500,
        showConfirmButton: false,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
@endif
</script>
