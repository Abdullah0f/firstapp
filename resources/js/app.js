import "./bootstrap";

import Search from "./live-search";
import Chat from "./chat";
if (document.querySelector(".header-search-icon")) {
    new Search();
}

if (document.querySelector("#chat-wrapper")) {
    new Chat();
}
