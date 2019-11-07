const Menu = [
  { header: "Menu" },
  // {
  //   title: 'Dashboard',
  //   group: 'apps',
  //   icon: 'dashboard',
  //   name: 'Dashboard',
  // },
  {
    title: "Vecinos",
    group: "apps",
    icon: "people",
    name: "NeighborPage"
  },
  {
    title: "Invitados",
    group: "apps",
    icon: "portrait",
    name: "GuestPage"
  },
  {
    title: "Tickets",
    group: "apps",
    icon: "priority_high",
    name: "TicketPage"
  }
];
// reorder menu
Menu.forEach(item => {
  if (item.items) {
    item.items.sort((x, y) => {
      let textA = x.title.toUpperCase();
      let textB = y.title.toUpperCase();
      return textA < textB ? -1 : textA > textB ? 1 : 0;
    });
  }
});

export default Menu;
