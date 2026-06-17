<div align="center">

# TrustUs

With _all_ your data.

</div>

TrustUs is a web-based file transfer platform designed to make sharing files fast and effortless. Built with a lightweight stack using JavaScript, PHP, and HTML, it provides a straightforward way to upload and share files without unnecessary complexity.

## Features

-   Fast and easy file uploads
-   Simple file sharing experience
-   Lightweight and efficient architecture
-   No client installation required
-   Built with pure JavaScript, PHP, and HTML
-   File integrity validation using SHA-256 hash checks

## File Integrity

TrustUs includes an integrity check to detect whether uploaded files have been changed after upload.

When a file is uploaded, the system calculates a SHA-256 hash of the uploaded file and stores this hash in the database together with the file information. When the file is viewed or downloaded later, the system calculates the SHA-256 hash again and compares it with the stored hash.

SHA-256 was chosen because it produces a fixed 64-character hash and is suitable for detecting file changes. If even a small part of the file changes, the resulting hash will be different. This makes it useful for checking whether a file was manually modified, damaged, or corrupted after upload.

If the hashes do not match, the system blocks the preview or download and shows a safe error message without exposing technical details to the user.

This SHA-256 hash is used for file integrity validation, not for password storage.

## Languages

-   JavaScript: Frontend functionality.
-   PHP: Backend processing and file management.
-   HTML: User interface structure.

## Repository

This repository contains the complete source code for the TrustUs website, including both frontend and backend components.

## Getting Started

1. Clone the repository:
    ```bash
    git clone https://github.com/JTnadrooi/trustus.git
    ```