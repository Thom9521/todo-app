// Hostname
var hostname = window.location.protocol + "//" + window.location.hostname;

// Add port for localhost
if (window.location.hostname === "localhost") {
  hostname += ":8000";
}

// Version
var version = "1.1.3";

export default [hostname, version];
